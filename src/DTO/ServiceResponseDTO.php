<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

class ServiceResponseDTO {
  
  private mixed $data;
  private int $statusCode;
  
  public function __construct(mixed $data = [], int $statusCode = Response::HTTP_OK){
    $this->data = $data;
    $this->statusCode = $statusCode;
  }
  
  public function getData(): mixed {
    return $this->data;
  }
  
  public function getStatusCode(): int {
    return $this->statusCode;
  }
  
}