<?php

namespace Faw\OidcBundle;

use Faw\OidcBundle\Enum\OidcTokenType;
use Lcobucci\JWT\Validation\Constraint;

interface OidcTokenConstraintProviderInterface
{
  /**
   * Provide additional Token constraints to be checked during Token validation.
   *
   * @return Constraint[]
   */
  public function getAdditionalConstraints(OidcTokenType $tokenType): array;
}
