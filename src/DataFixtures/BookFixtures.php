<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $Genres = ['Fantastic','Biographie','Policier','Science Fiction'];
        $Languages = ['Francais','Italien','Allemand','Espagnol'];
        $Editors = ['Alba Michel','Maison du livre', 'Saison des Fleur','Maison Archet'];

        for ($i = 1; $i < 100; $i++) {
            $author = (new Book())
                ->setTitle($faker->sentence(4))
                ->setSynopsys($faker->paragraph(5))
                ->setYearPublished($faker->date('Y'))
                ->setISBN($faker->isbn10())
                ->setNbPage($faker->randomNumber(3, true))
                ->setAuthor($this->getReference($faker->numberBetween(1,99)))
                ->setGenre($this->getReference($faker->randomElement($Genres)))
                ->setEditor($this->getReference($faker->randomElement($Editors)))
                ->setIsOnLine(1);
                
                $manager->persist($author);
        }
      

        $manager->flush();
    }

    public function getDependencies()
    {
        return [AuthorFixtures::class, ExtraFixtures::class];
    }
}
