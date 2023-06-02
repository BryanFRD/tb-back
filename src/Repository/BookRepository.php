<?php

namespace App\Repository;

use App\DTO\RepositoryResponse;
use App\Entity\Book;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

class BookRepository extends AbstractRepository {
  
  public function __construct(ManagerRegistry $registry){
    parent::__construct($registry, Book::class);
  }
  
  public function getAll(array $params): RepositoryResponse {
    $queryBuilder = $this->getEntityManager()->createQueryBuilder();
    $queryBuilder
      ->select("b.id, b.title, b.createdAt, b.updatedAt, b.deletedAt, a.id AS authorId")
      ->addSelect("CASE WHEN a.deletedAt IS NULL THEN a.name ELSE 'Anonyme' END AS authorName")
      ->from(Book::class, "b")
      ->leftJoin("b.author", "a")
      ->groupBy("b.id")
      ->where(($params["includeDeleted"] ?? false) ? "1 = 1" : "b.deletedAt IS NULL")
      ->setFirstResult($params["offset"] ?? 0)
      ->setMaxResults($params["limit"] ?? 50);
      
    if(isset($params["search"])){
      $queryBuilder
        ->andWhere("b.title LIKE :search")
        ->setParameter("search", "%" . $params["search"] . "%");
    }
    
    $paginator = new Paginator($queryBuilder);
  
    $result = [
      "count" => $paginator->count(),
      "data" => $paginator->getQuery()->getResult()
    ];
    
    return new RepositoryResponse(data: $result, statusCode: Response::HTTP_OK);
  }
  
}