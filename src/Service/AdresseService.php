<?php
namespace App\Service;

use App\Entity\Adresse;
use App\Entity\Commande;
use App\Entity\User;
use App\Entity\UserAdressCommande;
use Doctrine\ORM\EntityManagerInterface;

class AdresseService
{
    private $entityManager;
    private $userAdressCommande;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userAdressCommande = new UserAdressCommande;
    }


    public function createAndPersistAdresse(array $adresseInfo, User $user, Commande $commande)
    {
        $adresse = new Adresse();
        $adresse->setNomComplet($adresseInfo['nomComplet']);
        $adresse->setAdresse($adresseInfo['adresse']);
        $adresse->setClient($user);
        $adresse->setCodePostal($adresseInfo['codePostal']);
        $adresse->setInstructionLivraison($adresseInfo['instructionLivraison'] ?? null);
        $adresse->setPays($adresseInfo['pays']);
        $adresse->setVille($adresseInfo['ville']);
        $adresse->setTelephone($adresseInfo['telephone']);
        $adresse->setCommande($commande);
        $adresse->setType($adresseInfo['type']);

        $this->entityManager->persist($adresse);

        $jointure = $this->userAdressCommande->setAdresse($adresse)->setCommande($commande)->setUser($user);

        $this->entityManager->persist($jointure);
    }


    public function persistExistingAdresse(Adresse $adresse, User $user, Commande $commande)
    {
        $jointure = new UserAdressCommande();
        $jointure->setAdresse($adresse);
        $jointure->setCommande($commande);
        $jointure->setUser($user);

        // Persistez la jointure
        $this->entityManager->persist($jointure);
    }

    public function sauvegarderAdresse()
    {
        // Flush pour appliquer les changements à la base de données
        $this->entityManager->flush();
    }
}