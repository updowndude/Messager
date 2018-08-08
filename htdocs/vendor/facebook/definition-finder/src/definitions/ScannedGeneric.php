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

class ScannedGeneric {
  const type TConstraint =
    shape('type' => string, 'relationship' => RelationshipToken);

  public function __construct(
    private string $name,
    private VarianceToken $variance,
    private ImmVector<self::TConstraint> $constraints,
  ) {
  }

  public function getName(): string {
    return $this->name;
  }

  public function getConstraints(): ImmVector<self::TConstraint> {
    return $this->constraints;
  }

  public function isContravariant(): bool {
    return $this->variance === VarianceToken::CONTRAVARIANT;
  }

  public function isInvariant(): bool {
    return $this->variance === VarianceToken::INVARIANT;
  }

  public function isCovariant(): bool {
    return $this->variance === VarianceToken::COVARIANT;
  }
}
