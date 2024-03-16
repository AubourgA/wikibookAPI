<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use App\Entity\Genre;
use App\Entity\Language;
use App\Entity\Status;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ExtraFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      



        $statuses = ['Disponible','Réservé','Emprunté'];
        $Genres = ['Fantastic','Biographie','Policier','Science Fiction'];
        $Languages = ['Francais','Italien','Allemand','Espagnol'];
        $Editors = ['Alba Michel','Maison du livre', 'Saison des Fleur','Maison Archet'];

        foreach ($statuses as $s) {
            $status = (new Status())
                ->setType($s); 
                $this->addReference($s, $status); 
                $manager->persist($status);
                
        }

        foreach ($Genres as $g) {
            $genre = (new Genre())
                ->setName($g);
            $this->addReference($g, $genre);
             $manager->persist($genre);
                
        }

        foreach ($Languages as $l) {
            $language = (new Language())
                ->setName($l); 
            $this->addReference($l, $language); 
            $manager->persist($language);   
        }

        foreach ($Editors as $e) {
            $editor = (new Editor())
                ->setName($e);  
                $this->addReference($e, $editor);
                $manager->persist($editor);   
        }



        $manager->flush();
    }
}
