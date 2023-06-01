<?php

namespace App\Service;

use App\DTO\ServiceResponse;
use App\Repository\AbstractRepository;
use App\Validator\AbstractRepositoryValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

abstract class AbstractRepositoryService {
  
  protected AbstractRepository $repository;
  protected AbstractRepositoryValidator $validator;
  
  public function __construct(AbstractRepository $repository, AbstractRepositoryValidator $validator){
    $this->repository = $repository;
    $this->validator = $validator;
  }
  
  public function getAll(array $params): ServiceResponse {
    $validatorResponse = $this->validator->validate($params, $this->validator->getParamsConstraint());
    
    if(!empty($validatorResponse->getViolations())){
      return new ServiceResponse($validatorResponse, Response::HTTP_BAD_REQUEST);
    }
    
    return new ServiceResponse($this->repository->getAll($params));
  }
  
  public function getById(Ulid $id): ServiceResponse {
    $response = $this->repository->getById($id);
    
    if(!empty($response->getErrors())){
      return new ServiceResponse($response->getErrors(), $response->getStatusCode());
    }
    
    return new ServiceResponse($response->getData(), $response->getStatusCode());
  }
  
  public abstract function save(array $body, bool $flush = true): ServiceResponse;
  
  public abstract function update(Ulid $id, array $body, bool $flush = true): ServiceResponse;
  
  public function delete(Ulid $id, bool $flush = true, bool $soft = true): ServiceResponse {
    $response = $this->repository->getById($id);
    
    if(!empty($response->getErrors())){
      return new ServiceResponse($response->getErrors(), $response->getStatusCode());
    }
    
    $response = $this->repository->delete($response->getData(), $flush, $soft);
    if(!empty($response->getErrors())){
      return new ServiceResponse($response->getErrors(), $response->getStatusCode());
    }
    
    return new ServiceResponse($response->getData(), $response->getStatusCode());
  }
  
}