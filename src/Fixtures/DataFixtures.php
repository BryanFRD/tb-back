<?php

namespace App\Fixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class DataFixtures extends Fixture {
  
  public function load(ObjectManager $manager): void {
    $faker = Faker::create("fr_FR");
    
    for($i = 0; $i < 10; $i++){
      $author = new Author();
      $author
        ->setName($faker->unique()->name())
        ->updateTimestamps();
      
      $manager->persist($author);
      
      for($j = 0; $j < rand(0, 5); $j++){
        $book = new Book();
        $book
          ->setTitle($faker->word())
          ->setAuthor($author)
          ->updateTimestamps();
        
        $manager->persist($book);
      }
      
      $manager->flush();
    }
  }
  
}