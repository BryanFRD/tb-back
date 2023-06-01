<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

class AuthorValidator extends AbstractRepositoryValidator {
  
  public function getParamsConstraint(): Collection {
    return new Collection([
      "includeDeleted" => new Optional([new Type("boolean")]),
      "offset" => new Optional([new Type("integer")]),
      "limit" => new Optional([new Type("integer")]),
      "search" => new Optional([new Type("string")])
    ]);
  }
  
  public function getSaveConstraint(): Collection {
    return new Collection([
      "name" => new Type("string")
    ]);
  }
  
  public function getUpdateConstraint(): Collection {
    return new Collection([
      "name" => new Optional([new Type("string")])
    ]);
  }
  
}