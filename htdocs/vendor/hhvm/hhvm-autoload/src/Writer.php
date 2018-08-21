<?hh // strict
/*
 *  Copyright (c) 2015-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the BSD-style license found in the
 *  LICENSE file in the root directory of this source tree. An additional grant
 *  of patent rights can be found in the PATENTS file in the same directory.
 *
 */

namespace Facebook\AutoloadMap;

final class Writer {
  private ?ImmVector<string> $files;
  private ?AutoloadMap $map;
  private ?string $root;
  private bool $relativeAutoloadRoot = true;
  private ?string $failureHandler;
  private bool $isDev = true;

  public function setIsDev(bool $is_dev): this {
    $this->isDev = $is_dev;
    return $this;
  }

  public function setFailureHandler(?string $handler): this {
    $this->failureHandler = $handler;
    return $this;
  }

  public function setFiles(ImmVector<string> $files): this {
    $this->files = $files;
    return $this;
  }

  public function setAutoloadMap(AutoloadMap $map): this {
    $this->map = $map;
    return $this;
  }

  public function setBuilder(Builder $builder): this {
    $this->files = $builder->getFiles();
    $this->map = $builder->getAutoloadMap();
    return $this;
  }

  public function setRoot(string $root): this {
    $this->root = realpath($root);
    return $this;
  }

  public function setRelativeAutoloadRoot(bool $relative): this {
    $this->relativeAutoloadRoot = $relative;
    return $this;
  }

  public function writeToFile(
    string $destination_file,
  ): this {
    $files = $this->files;
    $map = $this->map;
    $is_dev = $this->isDev;

    if ($files === null) {
      throw new Exception('Call setFiles() before writeToFile()');
    }
    if ($map === null) {
      throw new Exception('Call setAutoloadMap() before writeToFile()');
    }
    if ($is_dev === null) {
      throw new Exception('Call setIsDev() before writeToFile()');
    }
    $is_dev = $is_dev ? 'true' : 'false';

    if ($this->relativeAutoloadRoot) {
      $root = '__DIR__.\'/../\'';
      $requires = $files->map(
        $file ==> '__DIR__.'.var_export(
          '/../'.$this->relativePath($file),
          true,
        ),
      );
    } else {
      $root = var_export($this->root.'/', true);
      $requires = $files->map(
        $file ==> var_export(
          $this->root.'/'.$this->relativePath($file),
          true,
        ),
      );
    }

    $requires = implode(
      "\n",
      $requires->map($require ==> 'require_once('.$require.');'),
    );

    $map = array_map(
      function ($sub_map): mixed {
        assert(is_array($sub_map));
        return array_map(
          $path ==> $this->relativePath($path),
          $sub_map,
        );
      },
      Shapes::toArray($map),
    );

    $failure_handler = $this->failureHandler;
    if ($failure_handler !== null) {
      if (substr($failure_handler, 0, 1) !== '\\') {
        $failure_handler = '\\'.$failure_handler;
      }
    }

    if ($failure_handler !== null) {
      $add_failure_handler = sprintf(
        "if (%s::isEnabled()) {\n".
        "  \$handler = new %s();\n".
        "  \$map['failure'] = [\$handler, 'handleFailure'];\n".
        "  \HH\autoload_set_paths(\$map, root());\n".
        "  \$handler->initialize();\n".
        "}",
        $failure_handler,
        $failure_handler,
      );
    } else {
      $add_failure_handler = null;
    }

    $build_id = var_export(
      date(\DateTime::ATOM).'!'.bin2hex(random_bytes(16)),
      true,
    );

    $map = var_export($map, true);
    $code = <<<EOF
<?php

/// Generated file, do not edit by hand ///

namespace Facebook\AutoloadMap\Generated;

function build_id() {
  return $build_id;
}

function root() {
  return $root;
}

function is_dev() {
  return $is_dev;
}

function map() {
  return $map;
}

$requires

\$map = map();

\HH\autoload_set_paths(\$map, root());
foreach (\spl_autoload_functions() ?: [] as \$autoloader) {
  \spl_autoload_unregister(\$autoloader);
}

$add_failure_handler
EOF;
    file_put_contents(
      $destination_file,
      $code,
    );

    return $this;
  }

  <<__Memoize>>
  private function relativePath(
    string $path,
  ): string {
    $root = $this->root;
    if ($root === null) {
      throw new Exception('Call setRoot() before writeToFile()');
    }
    $path = realpath($path);
    if (strpos($path, $root) !== 0) {
      throw new Exception(
        "%s is outside root %s",
        $path,
        $root,
      );
    }
    return substr($path, strlen($root) + 1);
  }
}