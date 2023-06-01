<?php

namespace App\DTO;

class ValidatorResponse {
  
  private array $violations;
  
  public function __construct(array $violations){
    $this->violations = $violations;
  }
  
  public function getViolations(): array {
    return $this->violations;
  }
  
}