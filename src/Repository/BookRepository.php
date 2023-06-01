<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends AbstractRepository {
  
  public function __construct(ManagerRegistry $registry){
    parent::__construct($registry, Book::class);
  }
  
}