<?php

namespace App\DataFixtures;

use App\Entity\Membre;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreFixtures extends Fixture
{
    private $hasher ;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for($i = 0 ; $i <= 5 ; $i++){
            $membre = new Membre();
            $membre-> setPseudo($faker->name())
                ->setDateEnregistrement(new \DateTime('now'))
                ->setNom($faker->name())
                ->setPrenom($faker->firstName)
                ->setCivilite($faker->randomElement(['m', 'f']))
                ->setEmail($faker->email);
            if($i === 0){
                $membre->setStatut(0);
            }else {
                $membre->setStatut(1);
            }
            $hashedPassword = $this->hasher->hashPassword($membre , "demo");
            $membre->setMdp($hashedPassword);
            $manager->persist($membre);
            $manager->flush();

            $this->addReference("membre_{$i}" , $membre) ;
        }
    }
}