<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Plantes;
use App\Entity\Commandes;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create();
        $user = [];

        for ($i = 0; $i < 50; $i++) {
            $users = new User();
            $users->setNom($faker->name);
            $users->setPrenom($faker->firstName);
            $users->setEmail($faker->email);
            $users->setPassword($faker->password);
            $users->setTelephone($faker->phoneNumber);
            $manager->persist($users);
            $user[] = $users;
        }

        $plantes = [];

        for ($i = 0; $i < 50; $i++) {
            $plante = new Plantes();
            $plante->setNom($faker->firstName);
            $plante->setDescription($faker->sentence);
            $plante->setPrix($faker->randomFloat(2, 0, 1));
            $manager->persist($plante);
            $plantes[] = $plante;
        }

        $commandes = [];

        for ($i = 0; $i < 50; $i++) {
            $commande = new Commandes();
            $commande->setDateCommande($faker->dateTimeBetween("-30 days", "now"));
            $commande->setEtatCommande($faker->choose("en preparation", "en cours de livraison"));
            $manager->persist($commande);
            $commandes[] = $commande;
        }



        $manager->flush();
    }
}
