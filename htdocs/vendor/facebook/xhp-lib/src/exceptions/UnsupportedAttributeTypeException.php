<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

class XHPUnsupportedAttributeTypeException extends XHPException {
  public function __construct(
    :xhp $that,
    string $type,
    string $attr,
    string $reason,
  ) {
    parent::__construct(
      "Attribute `$attr` in element `".
      :xhp::class2element(get_class($that)).
      "` has unsupported type `$type`: $reason",
    );
  }
}
