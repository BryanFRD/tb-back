<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book extends AbstractEntity {
  
  #[ORM\Column]
  protected string $title;
  
  #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: "books")]
  protected Author $author;
  
  public function getTitle(): string {
    return $this->title;
  }
  
  public function setTitle(string $title): self {
    $this->title = $title;
    return $this;
  }
  
  public function getAuthor(): Author {
    return $this->author;
  }
  
  public function setAuthor(Author $author): self {
    $this->author = $author;
    return $this;
  }
  
  public function jsonSerialize(): mixed {
    return array_merge(parent::jsonSerialize(),
      array(
        "title" => $this->getTitle(),
        "authorId" => $this->getAuthor()->getId(),
        "authorName" => $this->getAuthor()->getDeletedAt() === null ? $this->getAuthor()->getName() : "Anonyme"
      ),
    );
  }
  
}