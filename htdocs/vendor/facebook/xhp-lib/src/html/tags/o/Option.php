<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

class :option extends :xhp:pcdata-element {
  attribute
    bool disabled,
    Stringish label,
    bool selected,
    Stringish value;
  protected string $tagName = 'option';
}
