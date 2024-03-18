<?php 
namespace App\Service;

use App\Entity\Commande;

class CommandeService
{

public function createNewCommande($user): Commande
{
$commande = new Commande();
$commande->setNumeroCommande($this->generateNumeroCommande());
$commande->setClient($user);
$commande->setDateCommande(new \DateTimeImmutable());
$commande->setEtatCommande('En Attente');

return $commande;
}

private function generateNumeroCommande(): string
{
// Générer un numéro de commande unique ici
return 'CMD-' . uniqid();
}
}