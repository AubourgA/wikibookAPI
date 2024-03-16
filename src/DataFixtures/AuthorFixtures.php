<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Author;
use App\Entity\Nationality;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        $faker = Factory::create();

        $nationalities = ['Franciase','Allemande','Espagnole'];

        foreach ($nationalities as $n) {
            $nationality = (new Nationality())
                ->setCountry($n);  
                $manager->persist($nationality);
                $this->addReference($n, $nationality);
        }
       
        for ($i = 1; $i < 100; $i++) {
            $author = (new Author())
                ->setName($faker->lastName())
                ->setFirstname($faker->firstName())
                ->setBirthdate($faker->dateTime())
                ->setNationality( $this->getReference($faker->randomElement($nationalities))); 
            
            $this->addReference($i, $author);
            $manager->persist($author);
        }
        

        $manager->flush();
    }
}
