<?php

namespace App\Entity;

use App\Repository\DetailsCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailsCommandeRepository::class)]
class DetailsCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?float $prix_unitaire = null;

    #[ORM\Column]
    private ?float $sous_total = null;

    #[ORM\ManyToOne(inversedBy: 'detailsCommandes')]
    #[ORM\JoinColumn(name: 'plante_id', referencedColumnName: 'id')]
    private ?Plantes $plante_id = null;

    #[ORM\ManyToOne(inversedBy: 'detailsCommandes')]
    #[ORM\JoinColumn(name: 'commande_id', referencedColumnName: 'id')]
    private ?Commandes $commande_id = null;

    #[ORM\Column]
    private ?float $total = null;

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

    public function getPrixUnitaire(): ?float
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire(float $prix_unitaire): static
    {
        $this->prix_unitaire = $prix_unitaire;

        return $this;
    }

    public function getSousTotal(): ?float
    {
        return $this->sous_total;
    }

    public function setSousTotal(float $sous_total): static
    {
        $this->sous_total = $sous_total;

        return $this;
    }

    public function getPlanteId(): ?plantes
    {
        return $this->plante_id;
    }

    public function setPlanteId(?plantes $plante_id): static
    {
        $this->plante_id = $plante_id;

        return $this;
    }

    public function getCommandeId(): ?commandes
    {
        return $this->commande_id;
    }

    public function setCommandeId(?commandes $commande_id): static
    {
        $this->commande_id = $commande_id;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }
}
