<?php

namespace App\DataFixtures;

use App\Entity\Commandes;
use App\Entity\Plantes;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create();

        $users = [];

        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setEmail($faker->email);
            $user->setMotDePasse($faker->password);
            $user->setTelephone($faker->randomDigitNotZero);
            $manager->persist($user);
            $users[] = $user;
        }

        $plantes = [];

        for ($i = 0; $i < 50; $i++) {
            $plante = new Plantes();
            $plante->setNomPlante($faker->firstName);
            $plante->setDescriptionPlante($faker->sentence);
            $plante->setPrixPlante($faker->randomFloat(2, 30, 740));
            $plante->setStock($faker->randomNumber(1, 750));
            $manager->persist($plante);
            $plantes[] = $plante;
        }

        $commandes = [];

        for ($i = 0; $i < 50; $i++) {
            $commande = new Commandes();
            $commande->setDateCommande($faker->dateTimeBetween("-20 days", "now"));
            $commande->setEtatCommande($faker->sentences);
            $manager->persist($commande);
            $commandes[] = $commande;
        }
        $manager->flush();
    }
}
