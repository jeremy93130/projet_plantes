<?php

namespace App\Entity;

use App\Repository\PlantesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlantesRepository::class)]
class Plantes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_plante = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description_plante = null;

    #[ORM\Column]
    private ?float $prix_plante = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $caracteristiques = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $entretien = null;

    private ?string $search;

    #[ORM\ManyToMany(targetEntity: DetailsCommandes::class, mappedBy: 'plante')]
    private Collection $detailscommandes;

    #[ORM\OneToMany(mappedBy: 'plante', targetEntity: Quantites::class, cascade: ["persist"])]
    private Collection $quantites;

    public function __construct()
    {
        $this->detailscommandes = new ArrayCollection();
        $this->quantites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPlante(): ?string
    {
        return $this->nom_plante;
    }

    public function setNomPlante(string $nom_plante): static
    {
        $this->nom_plante = $nom_plante;

        return $this;
    }

    public function getDescriptionPlante(): ?string
    {
        return $this->description_plante;
    }

    public function setDescriptionPlante(string $description_plante): static
    {
        $this->description_plante = $description_plante;

        return $this;
    }

    public function getPrixPlante(): ?float
    {
        return $this->prix_plante;
    }

    public function setPrixPlante(float $prix_plante): static
    {
        $this->prix_plante = $prix_plante;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCaracteristiques(): ?string
    {
        return $this->caracteristiques;
    }

    public function setCaracteristiques(string $caracteristiques): static
    {
        $this->caracteristiques = $caracteristiques;

        return $this;
    }

    public function getEntretien(): ?string
    {
        return $this->entretien;
    }

    public function setEntretien(string $entretien): static
    {
        $this->entretien = $entretien;

        return $this;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch($search): static
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return Collection<int, DetailsCommandes>
     */
    public function getDetailscommandes(): Collection
    {
        return $this->detailscommandes;
    }

    public function addDetailscommande(DetailsCommandes $detailscommande): static
    {
        if (!$this->detailscommandes->contains($detailscommande)) {
            $this->detailscommandes->add($detailscommande);
            $detailscommande->addPlante($this);
        }

        return $this;
    }

    public function removeDetailscommande(DetailsCommandes $detailscommande): static
    {
        if ($this->detailscommandes->removeElement($detailscommande)) {
            $detailscommande->removePlante($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Quantites>
     */
    public function getQuantites(): Collection
    {
        return $this->quantites;
    }

    public function addQuantite(Quantites $quantite): static
    {
        if (!$this->quantites->contains($quantite)) {
            $this->quantites->add($quantite);
            $quantite->setPlante($this);
        }

        return $this;
    }

    public function removeQuantite(Quantites $quantite): static
    {
        if ($this->quantites->removeElement($quantite)) {
            // set the owning side to null (unless already changed)
            if ($quantite->getPlante() === $this) {
                $quantite->setPlante(null);
            }
        }

        return $this;
    }
}
