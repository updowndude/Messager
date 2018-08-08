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

namespace Facebook\DefinitionFinder;

abstract class Consumer {
  const type TContext = shape(
    'filename' => string,
    'sourceType' => SourceType,
    'namespace' => ?string,
    'usedTypes' => ImmMap<string, string>,
    'usedNamespaces' => ImmMap<string, string>,
    'genericTypeNames' => ImmSet<string>,
  );

  protected function getBuilderContext(): ScannedBaseBuilder::TContext {
    return shape(
      'position' => shape(
        'filename' => $this->context['filename'],
        'line' => $this->tq->getLine(),
      ),
      'sourceType' => nullthrows(Shapes::idx($this->context, 'sourceType')),
    );
  }

  protected function assertValidSourceType(): void {
    $type = $this->context['sourceType'];
    invariant(
      $type !== SourceType::NOT_YET_DETERMINED,
      "Can't consume without a source type (Hack vs PHP affects namespace ".
      "resolution - eg classes called 'string' or 'Collection'",
    );

    invariant(
      $type !== SourceType::MULTIPLE_FILES,
      'Token streams come from a single file, so MULTIPLE_FILES is not a '.
      'valid source type.',
    );
  }

  private static ?ImmSet<string> $autoImportTypes;

  final private function getAutoImportTypes(): ImmSet<string> {
    $scalars = ImmSet {
      'mixed',
      'void',
      'bool',
      'int',
      'string',
      'float',
      'double',
      'callable',
      'resource',
      'num',
      'arraykey',
      'array',
    };

    if ($this->context['sourceType'] === SourceType::PHP) {
      return $scalars;
    }

    /* class and typedef list taken from typechecker source:
     *   hhvm/hphp/hack/src/parsing/namespaces.ml
     * Last updated:
     *   2016-09-20
     *   3a26de9eb51f041f1cf2df34c06d47e7c0c27015
     */
    $classes = ImmSet {
      'Traversable',
      'KeyedTraversable',
      'Container',
      'KeyedContainer',
      'Iterator',
      'KeyedIterator',
      'Iterable',
      'KeyedIterable',
      'Collection',
      'Vector',
      'ImmVector',
      'vec',
      'dict',
      'keyset',
      'Map',
      'ImmMap',
      'StableMap',
      'Set',
      'ImmSet',
      'Pair',
      'Awaitable',
      'AsyncIterator',
      'IMemoizeParam',
      'AsyncKeyedIterator',
      'InvariantException',
      'AsyncGenerator',
      'WaitHandle',
      'StaticWaitHandle',
      'WaitableWaitHandle',
      'ResumableWaitHandle',
      'AsyncFunctionWaitHandle',
      'AsyncGeneratorWaitHandle',
      'AwaitAllWaitHandle',
      'ConditionWaitHandle',
      'RescheduleWaitHandle',
      'SleepWaitHandle',
      'ExternalThreadEventWaitHandle',
      'Shapes',
      'TypeStructureKind',
    };

    $typedefs = ImmSet { 'typename', 'classname', 'TypeStructure' };

    $types = $scalars->concat($classes)->concat($typedefs)->toImmSet();

    self::$autoImportTypes = $types;
    return $types;
  }

  <<__Deprecated('Please send a pull request adding the missing types')>>
  final public static function setAutoImportTypes(ImmSet<string> $types): void {
    self::$autoImportTypes = $types;
  }

  public function __construct(
    protected TokenQueue $tq,
    protected self::TContext $context,
  ) {
    $namespace = $context['namespace'];
    invariant(
      $namespace === null || substr($namespace, -1) !== '\\',
      "Namespaces don't end with slashes",
    );
    $this->assertValidSourceType();
  }

  protected function consumeWhitespace(): void {
    while (!$this->tq->isEmpty()) {
      list($_, $ttype) = $this->tq->peek();
      if ($ttype === T_WHITESPACE || $ttype === T_COMMENT) {
        $this->tq->shift();
        continue;
      }
      break;
    }
  }

  protected function consumeStatement(): void {
    $first = null;
    while ($this->tq->haveTokens()) {
      list($tv, $ttype) = $this->tq->shift();
      if ($first === null) {
        $first = $tv;
      }
      if ($tv === ';') {
        return;
      }
      if ($tv === '{') {
        $this->consumeBlock();
        if ($first === '{') {
          return;
        }
      }
    }
  }

  protected function skipToBlock(): void {
    while ($this->tq->haveTokens()) {
      list($next, $next_type) = $this->tq->shift();
      if (
        $next === '{' ||
        $next_type === T_CURLY_OPEN ||
        $next_type === T_DOLLAR_OPEN_CURLY_BRACES
      ) {
        return;
      }
    }
    invariant_violation('no block');
  }

  protected function consumeBlock(): void {
    $nesting = 1;
    while ($this->tq->haveTokens()) {
      list($next, $next_type) = $this->tq->shift();
      if (
        $next === '{' ||
        $next_type === T_CURLY_OPEN ||
        $next_type === T_DOLLAR_OPEN_CURLY_BRACES
      ) {
        ++$nesting;
      } else if ($next === '}') { // no such thing as T_CURLY_CLOSE
        --$nesting;
        if ($nesting === 0) {
          return;
        }
      }
    }
  }

  protected function normalizeNullableName(?string $name): ?string {
    if ($name === null) {
      return null;
    }
    return $this->normalizeName($name);
  }

  protected function normalizeName(
    string $name,
    NameNormalizationMode $mode = NameNormalizationMode::REFERENCE,
  ): string {
    $name = $this->fullyQualifyName($name, $mode);
    if (substr($name, 0, 1) === '\\') {
      return substr($name, 1);
    }
    return $name;
  }

  private function fullyQualifyName(
    string $name,
    NameNormalizationMode $mode,
  ): string {
    if (substr($name, 0, 1) === "\\") {
      return $name;
    }

    if ($mode === NameNormalizationMode::REFERENCE) {
      $autoimport = $this->getAutoImportTypes();
      if ($autoimport->contains($name)) {
        return $name;
      }
    }

    if (preg_match('/^(this|self|static)(::|$)/', $name)) {
      return $name;
    }

    if (substr($name, 0, 6) === 'shape(') {
      return $name;
    }

    if (substr($name, 0, 1) === '(') {
      // Callable or tuple
      return $name;
    }

    if ($this->context['genericTypeNames']->contains($name)) {
      return $name;
    }

    $parts = explode('\\', $name);
    $base = $parts[0];
    $real_base = null;
    if (count($parts) === 1) {
      $real_base = $this->context['usedTypes']->get($base);
    } else {
      $real_base = $this->context['usedNamespaces']->get($base);
    }

    if ($real_base === null) {
      return $this->context['namespace'].'\\'.$name;
    }

    $parts[0] = $real_base;
    return '\\'.implode('\\', $parts);
  }

  final protected function getContextWithGenerics(
    \ConstVector<ScannedGeneric> $generics,
  ): self::TContext {
    $context = $this->context;
    $context['genericTypeNames'] = $context['genericTypeNames']
      ->concat($generics->map($generic ==> $generic->getName()))
      ->toImmSet();
    return $context;
  }
}
