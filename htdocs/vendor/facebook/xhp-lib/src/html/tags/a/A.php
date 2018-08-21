<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

class :a extends :xhp:html-element {
  attribute
    Stringish download,
    Stringish href,
    Stringish hreflang,
    Stringish media,
    Stringish rel,
    Stringish target,
    Stringish type,
    // Legacy
    Stringish name;
  category %flow, %phrase, %interactive;
  // Should not contain %interactive
  children (pcdata | %flow)*;
  protected string $tagName = 'a';
}
