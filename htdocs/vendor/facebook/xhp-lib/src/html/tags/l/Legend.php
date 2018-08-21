<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

class :legend extends :xhp:html-element {
  children (pcdata | %phrase)*;
  protected string $tagName = 'legend';
}
