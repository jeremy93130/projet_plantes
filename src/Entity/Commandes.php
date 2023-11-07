<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column]
    private array $etat_commande = [];

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $client_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getEtatCommande(): array
    {
        return $this->etat_commande;
    }

    public function setEtatCommande(array $etat_commande): static
    {
        $this->etat_commande = $etat_commande;

        return $this;
    }

    public function getClientId(): ?user
    {
        return $this->client_id;
    }

    public function setClientId(?user $client_id): static
    {
        $this->client_id = $client_id;

        return $this;
    }
}
