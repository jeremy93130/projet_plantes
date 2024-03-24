<?php

namespace App\Entity;

use App\Repository\UserAdressCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAdressCommandeRepository::class)]
class UserAdressCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userAdressCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userAdressCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?adresse $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'userAdressCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAdresse(): ?adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?adresse $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCommande(): ?commande
    {
        return $this->commande;
    }

    public function setCommande(?commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
