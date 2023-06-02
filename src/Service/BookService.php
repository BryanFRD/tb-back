<?php

namespace App\Service;

use App\DTO\ServiceResponse;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Validator\BookValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

class BookService extends AbstractRepositoryService {
  
  protected AuthorRepository $authorRepository;
  
  public function __construct(BookRepository $repository, BookValidator $validator, AuthorRepository $authorRepository){
    parent::__construct($repository, $validator);
    
    $this->authorRepository = $authorRepository;
  }
  
  public function save(array $body, bool $flush = true): ServiceResponse {
    $validatorResponse = $this->validator->validate($body, $this->validator->getSaveConstraint());
    
    if(!empty($validatorResponse->getViolations())){
      return new ServiceResponse($validatorResponse->getViolations(), Response::HTTP_BAD_REQUEST);
    }
    
    $authorResponse = $this->authorRepository->getById(Ulid::fromString($body["authorId"]));
    
    if(!empty($authorResponse->getErrors())){
      return new ServiceResponse($authorResponse->getErrors(), $authorResponse->getStatusCode());
    }
    
    $book = new Book();
    $book
      ->setTitle($body["title"])
      ->setAuthor($authorResponse->getData())
      ->updateTimestamps();
    
    $response = $this->repository->save($book, $flush);
    
    if(!empty($response->getErrors())){
      return new ServiceResponse($response->getErrors(), Response::HTTP_BAD_REQUEST);
    }
    
    return new ServiceResponse($response->getData(), Response::HTTP_CREATED);
  }
  
  public function update(Ulid $id, array $body, bool $flush = true): ServiceResponse {
    $validatorResponse = $this->validator->validate($body, $this->validator->getUpdateConstraint());
    
    if(!empty($validatorResponse->getViolations())){
      return new ServiceResponse($validatorResponse->getViolations(), Response::HTTP_BAD_REQUEST);
    }
    
    $bookResponse = $this->repository->getById($id);
    
    if(isset($body["title"])){
      $bookResponse->getData()->setTitle($body["title"]);
    }
    
    if(isset($body["authorId"])){
      $authorResponse = $this->authorRepository->getById(Ulid::fromString($body["authorId"]));
      
      if(!empty($authorResponse->getErrors())){
        return new ServiceResponse($authorResponse->getErrors(), $authorResponse->getStatusCode());
      } else if(empty($authorResponse->getData())) {
        return new ServiceResponse(statusCode: Response::HTTP_NOT_FOUND);
      }
      
      $bookResponse->getData()->setAuthor($authorResponse->getData());
    }
    
    $updateResponse = $this->repository->flush();
    
    if(!empty($updateResponse->getErrors())){
      return new ServiceResponse($updateResponse->getErrors(), $updateResponse->getStatusCode());
    }
    
    return new ServiceResponse($bookResponse->getData(), Response::HTTP_OK);
  }
  
}