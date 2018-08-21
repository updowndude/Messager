<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

class :source extends :xhp:html-singleton {
  attribute
    Stringish media,
    Stringish sizes,
    Stringish src,
    Stringish srcset,
    Stringish type;
  protected string $tagName = 'source';
}
