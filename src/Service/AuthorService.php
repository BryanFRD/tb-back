<?php

namespace App\Service;

use App\DTO\ServiceResponse;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Validator\AuthorValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

class AuthorService extends AbstractRepositoryService {
  
  public function __construct(AuthorRepository $repository, AuthorValidator $validator){
    parent::__construct($repository, $validator);
  }
  
  public function save(array $body, bool $flush = true): ServiceResponse {
    $validatorResponse = $this->validator->validate($body, $this->validator->getSaveConstraint());
    
    if(!empty($validatorResponse->getViolations())){
      return new ServiceResponse($validatorResponse->getViolations(), Response::HTTP_BAD_REQUEST);
    }
    
    $author = new Author();
    $author
      ->setName($body["name"])
      ->updateTimestamps();
    
    $response = $this->repository->save($author, $flush);
    
    if(!empty($response->getErrors())){
      return new ServiceResponse($response->getErrors(), $response->getStatusCode());
    }
    
    return new ServiceResponse($response->getData(), Response::HTTP_CREATED);
  }
  
  public function update(Ulid $id, array $body, bool $flush = true): ServiceResponse {
    $validatorResponse = $this->validator->validate($body, $this->validator->getUpdateConstraint());
    
    if(!empty($validatorResponse->getViolations())){
      return new ServiceResponse($validatorResponse->getViolations(), Response::HTTP_BAD_REQUEST);
    }
    
    $authorResponse = $this->repository->getById($id);
    
    if(isset($body["name"])){
      $authorResponse->getData()->setName($body["name"]);
    }
    
    $updateResponse = $this->repository->flush();
    
    if(!empty($updateResponse->getErrors())){
      return new ServiceResponse($updateResponse->getErrors(), $updateResponse->getStatusCode());
    }
    
    return new ServiceResponse($authorResponse->getData(), Response::HTTP_OK);
  }
  
}