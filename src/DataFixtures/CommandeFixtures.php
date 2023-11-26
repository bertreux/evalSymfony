<?php

namespace App\DataFixtures;

use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Entity\Membre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommandeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 100; $i++) {

            $vehicule = $this->getReference("vehicule_" . $faker->numberBetween(0, 5));
            $membre = $this->getReference("membre_" . $faker->numberBetween(0, 5));

            $startDate = $faker->dateTimeThisDecade;
            $endDate = $faker->dateTimeInInterval($startDate, '+30 days');

            $commande = new Commande();
            $commande->setDateHeurFin($endDate)
                ->setDateHeurDepart($startDate)
                ->setDateEnregistrement(new \DateTime('now'))
                ->setMembre($membre)
                ->setVehicule($vehicule);
            $manager->persist($commande);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VehiculeFixtures::class,
            MembreFixtures::class
        ];
    }
}