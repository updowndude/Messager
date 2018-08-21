<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

class :fieldset extends :xhp:html-element {
  attribute
    bool disabled,
    Stringish form,
    Stringish name;
  category %flow;
  children (:legend?, (pcdata | %flow)*);
  protected string $tagName = 'fieldset';
}
