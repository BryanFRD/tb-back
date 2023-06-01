<?php

namespace App\Validator;

class BookValidator extends AbstractRepositoryValidator {
  
  public function getParamsConstraint(){
    return null;
  }
  
  public function getSaveConstraint(){
    return null;
  }
  
  public function getUpdateConstraint(){
    return null;
  }
  
}