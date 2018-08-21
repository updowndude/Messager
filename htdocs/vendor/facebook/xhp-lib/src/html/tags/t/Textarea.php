<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

class :textarea extends :xhp:pcdata-element {
  attribute
    enum {'on', 'off'} autocomplete,
    bool autofocus,
    int cols,
    Stringish dirname,
    bool disabled,
    Stringish form,
    int maxlength,
    int minlength,
    Stringish name,
    Stringish placeholder,
    bool readonly,
    bool required,
    int rows,
    enum {'soft', 'hard'} wrap;
  category %flow, %phrase, %interactive;
  protected string $tagName = 'textarea';
}
