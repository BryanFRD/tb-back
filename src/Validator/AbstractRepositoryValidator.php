<?php

namespace App\Validator;

use App\DTO\ValidatorResponse;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRepositoryValidator {
  
  private ValidatorInterface $validator;
  
  public function __construct(ValidatorInterface $validator){
    $this->validator = $validator;
  }
  
  public function validate($data, $constraint): ValidatorResponse {
    $errors = $this->validator->validate($data, $constraint);
    
    $errorsArray = array();
    if(count($errors) > 0){
      foreach($errors as $error){
        array_push($errorsArray, [$error->getPropertyPath(), $error->getMessage()]);
      }
    }
    
    return new ValidatorResponse($errorsArray);
  }
  
  public abstract function getParamsConstraint(): Collection;
  
  public abstract function getSaveConstraint(): Collection;
  
  public abstract function getUpdateConstraint(): Collection;
  
}