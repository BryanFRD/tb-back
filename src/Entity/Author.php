<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author extends AbstractEntity {
  
  #[ORM\Column]
  protected string $name;
  
  #[ORM\OneToMany(targetEntity: Book::class, mappedBy: "author")]
  protected ?Collection $books;
  
  public function getName(): string {
    return $this->name;
  }
  
  public function setName(string $name): self {
    $this->name = $name;
    return $this;
  }
  
  public function getBooks(): ?Collection {
    return $this->books;
  }
  
  public function jsonSerialize(): mixed {
    return array_merge(parent::jsonSerialize(),
      array(
        "name" => $this->getName()
      ),
    );
  }
  
}