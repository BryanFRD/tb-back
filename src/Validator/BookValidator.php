<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class BookValidator extends AbstractRepositoryValidator {
  
  public function getParamsConstraint(): Collection {
    return new Collection([
      "includeDeleted" => new Optional([new Type("boolean")]),
      "offset" => new Optional([new Type("integer")]),
      "limit" => new Optional([new Type("integer")])
    ]);
  }
  
  public function getSaveConstraint(): Collection {
    return new Collection([
      "title" => new Type("string"),
      "authorId" => new Regex("[0-7][0-9A-HJKMNP-TV-Z]{25}")
    ]);
  }
  
  public function getUpdateConstraint(): Collection {
    return new Collection([
      "title" => new Optional([new Type("string")]),
      "authorId" => new Optional([new Regex("[0-7][0-9A-HJKMNP-TV-Z]{25}")])
    ]);
  }
  
}