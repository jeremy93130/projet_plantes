<?php

namespace App\Entity;

use App\Repository\QuantitesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuantitesRepository::class)]
class Quantites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'quantites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plantes $plante = null;

    #[ORM\ManyToOne(inversedBy: 'quantites')]
    private ?DetailsCommandes $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPlante(): ?Plantes
    {
        return $this->plante;
    }

    public function setPlante(?Plantes $plante): static
    {
        $this->plante = $plante;

        return $this;
    }

    public function getCommande(): ?DetailsCommandes
    {
        return $this->commande;
    }

    public function setCommande(?DetailsCommandes $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
