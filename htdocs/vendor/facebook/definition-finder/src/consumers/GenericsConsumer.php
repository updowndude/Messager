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

const int T_SUPER = 436;

class GenericsConsumer extends Consumer {
  public function getGenerics(): \ConstVector<ScannedGeneric> {
    $tq = $this->tq;
    list($t, $ttype) = $tq->shift();
    invariant($ttype = T_TYPELIST_LT, 'Consuming generics, but not a typelist');

    $ret = Vector {};

    $name = null;
    $constraints = Vector {};
    $variance = VarianceToken::INVARIANT;

    while ($tq->haveTokens()) {
      list($t, $ttype) = $tq->shift();

      invariant($ttype !== T_TYPELIST_LT, "nested generic type");

      if ($ttype === T_WHITESPACE) {
        continue;
      }

      if ($ttype === T_TYPELIST_GT) {
        if ($name !== null) {
          $ret[] =
            new ScannedGeneric($name, $variance, $constraints->immutable());
        }
        return $ret;
      }

      if ($t === '-' || $t === '+') {
        $variance = VarianceToken::assert($t);
        continue;
      }

      if ($t === ',') {
        $ret[] = new ScannedGeneric(
          nullthrows($name),
          $variance,
          $constraints->immutable(),
        );
        $name = null;
        $constraints = Vector {};
        $variance = VarianceToken::INVARIANT;
        continue;
      }

      if ($name === null) {
        invariant(
          $ttype === T_STRING,
          'expected type variable name at line %d',
          $tq->getLine(),
        );
        $name = $t;
        continue;
      }

      if ($ttype === T_AS) {
        $this->consumeWhitespace();
        $constraint = (new TypehintConsumer($tq, $this->context))
          ->getTypehint()
          ->getTypeText();
        $constraints[] = shape(
          'type' => $constraint,
          'relationship' => RelationshipToken::SUBTYPE,
        );
        continue;
      }

      if ($ttype === T_SUPER) {
        $this->consumeWhitespace();
        $constraint = (new TypehintConsumer($tq, $this->context))
          ->getTypehint()
          ->getTypeText();
        $constraints[] = shape(
          'type' => $constraint,
          'relationship' => RelationshipToken::SUPERTYPE,
        );
        continue;
      }
    }
    invariant_violation('never reached end of generics definition');
  }
}
