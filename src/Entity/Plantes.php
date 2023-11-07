<?php

namespace App\Entity;

use App\Repository\PlantesRepository;
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
}
