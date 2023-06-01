<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

abstract class AbstractEntity {
  
  #[ORM\Id]
  #[ORM\Column(type: UlidType::NAME)]
  protected Ulid $id;
  
  #[ORM\Column(updatable: false)]
  protected DateTime $createdAt;
  
  #[ORM\Column]
  protected DateTime $updatedAt;
  
  #[ORM\Column]
  protected ?DateTime $deletedAt;
  
  public function getId(): Ulid {
    return $this->id;
  }
  
  public function getCreatedAt(): DateTime {
    return $this->createdAt;
  }
  
  public function getUpdatedAt(): DateTime {
    return $this->updatedAt;
  }
  
  public function getDeletedAt(): ?DateTime {
    return $this->deletedAt;
  }
  
  #[ORM\PrePersist]
  #[ORM\PreUpdate]
  public function updateTimestamps(): void {
    if(empty($this->createdAt)){
      $this->createdAt = date_create(timezone: new DateTimeZone('EUROPE/Paris'));
    }
    
    $this->updatedAt = date_create(timezone: new DateTimeZone('EUROPE/Paris'));
  }
  
  public function softDelete(): void {
    $this->deletedAt = date_create(timezone: new DateTimeZone('EUROPE/Paris'));
  }
  
}