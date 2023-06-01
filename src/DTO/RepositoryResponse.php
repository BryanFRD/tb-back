<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

class RepositoryResponse {
  
  private mixed $data;
  private ?array $errors;
  private int $statusCode;
  
  public function __construct(mixed $data = [], ?array $errors = null, int $statusCode = Response::HTTP_OK){
    $this->data = $data;
    $this->errors = $errors;
    $this->statusCode = $statusCode;
  }
  
  public function getData(): mixed {
    return $this->data;
  }
  
  public function getErrors(): ?array {
    return $this->errors;
  }
  
  public function getStatusCode(): int {
    return $this->statusCode;
  }
  
}