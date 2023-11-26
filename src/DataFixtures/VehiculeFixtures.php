<?php

namespace App\DataFixtures;

use App\Entity\Vehicule;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VehiculeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i = 0 ; $i <= 9 ; $i++){
            $vehicule = new Vehicule();
            $vehicule->setDateEnregistrement(new \DateTime('now'))
                    ->setPhoto('images/voiture'.($i+1).'.jpg')
                    ->setDescription($faker->paragraph)
                    ->setMarque($faker->word)
                    ->setTitre($faker->sentence)
                    ->setPrixJournalier(rand(100, 500))
                    ->setModele($faker->word);
            $manager->persist($vehicule);
            $manager->flush();

            $this->addReference("vehicule_{$i}" , $vehicule) ;
        }
    }
}