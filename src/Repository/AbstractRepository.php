<?php

namespace App\Repository;

use App\DTO\RepositoryResponse;
use App\Entity\AbstractEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

abstract class AbstractRepository extends ServiceEntityRepository {
  
  protected string $entityName;
  
  public function __construct(ManagerRegistry $registry, string $entityName){
    parent::__construct($registry, $entityName);
    
    $this->entityName = $entityName;
  }
  
  public function getAll(array $params): RepositoryResponse {
    $queryBuilder = $this->getEntityManager()->createQueryBuilder();
    $queryBuilder
      ->select("e")
      ->from($this->entityName, "e")
      ->where(($params["includeDeleted"] ?? false) ? "1 = 1" : "e.deletedAt IS NULL")
      ->setFirstResult($params["offset"] ?? 0)
      ->setMaxResults($params["limit"] ?? 50);
      
    $paginator = new Paginator($queryBuilder);
    
    $result = [
      "count" => $paginator->count(),
      "data" => $paginator->getQuery()->getResult()
    ];
    
    return new RepositoryResponse(data: $result, statusCode: Response::HTTP_OK);
  }
  
  public function getById(Ulid $id): RepositoryResponse {
    $entity = parent::find($id->toBinary());
    
    return new RepositoryResponse(data: $entity, statusCode: Response::HTTP_OK);
  }
  
  public function save(AbstractEntity $entity, bool $flush = true): RepositoryResponse {
    $this->getEntityManager()->persist($entity);
    
    if($flush){
      try {
        $this->getEntityManager()->flush();
        return new RepositoryResponse(statusCode: Response::HTTP_OK);
      } catch(ORMException $ex) {
        return new RepositoryResponse(errors: ["errors" => $ex], statusCode: Response::HTTP_CONFLICT);
      }
    }
    
    return new RepositoryResponse(data: $entity, statusCode: Response::HTTP_CREATED);
  }
  
  public function remove(AbstractEntity $entity, bool $flush = true, bool $soft = true): RepositoryResponse {
    if($soft){
      $entity->softDelete();
    } else {
      $this->getEntityManager()->remove($entity);
    }
    
    if($flush){
      try {
        $this->getEntityManager()->flush();
        return new RepositoryResponse(statusCode: Response::HTTP_OK);
      } catch(ORMException $ex) {
        return new RepositoryResponse(errors: ["errors" => $ex], statusCode: Response::HTTP_CONFLICT);
      }
    }
    
    return new RepositoryResponse();
  }
  
  public function flush(): RepositoryResponse {
    try {
      $this->getEntityManager()->flush();
      return new RepositoryResponse(statusCode: Response::HTTP_OK);
    } catch(ORMException $ex) {
      return new RepositoryResponse(errors: ["errors" => $ex], statusCode: Response::HTTP_CONFLICT);
    }
  }
  
}