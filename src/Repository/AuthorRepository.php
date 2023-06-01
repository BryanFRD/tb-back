<?php

namespace App\Repository;

use App\DTO\RepositoryResponse;
use App\Entity\Author;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

class AuthorRepository extends AbstractRepository {
  
  public function __construct(ManagerRegistry $registry){
    parent::__construct($registry, Author::class);
  }
  
  public function getAll(array $params): RepositoryResponse {
    $queryBuilder = $this->getEntityManager()->createQueryBuilder();
    $queryBuilder
      ->select("a.id, a.name, a.createdAt, a.updatedAt, a.deletedAt, COUNT(b) AS booksCount")
      ->from(Author::class, "a")
      ->leftJoin("a.books", "b")
      ->groupBy("a.id")
      ->where(($parmas["includeDeleted"] ?? false) ? "1 = 1" : "a.deletedAt IS NULL")
      ->setFirstResult($params["offset"] ?? 0)
      ->setMaxResults($params["limit"] ?? 50);
    
    $paginator = new Paginator($queryBuilder);
  
    $result = [
      "count" => $paginator->count(),
      "data" => $paginator->getQuery()->getResult()
    ];
    
    return new RepositoryResponse(data: $result, statusCode: Response::HTTP_OK);
  }
  
}