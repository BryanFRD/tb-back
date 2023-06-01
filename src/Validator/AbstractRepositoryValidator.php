<?php

namespace App\Validator;

use App\DTO\ValidatorResponse;

abstract class AbstractRepositoryValidator {
  
  public function __construct(){
    
  }
  
  public function validate($data, $constraint): ValidatorResponse {
    return new ValidatorResponse([]);
  }
  
  public abstract function getParamsConstraint();
  
  public abstract function getSaveConstraint();
  
  public abstract function getUpdateConstraint();
  
}