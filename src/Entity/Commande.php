<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Commande')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(length: 255)]
    private string $etatCommande = 'En Attente';

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: DetailsCommande::class)]
    private Collection $detailsCommandes;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: UserAdressCommande::class)]
    private Collection $userAdressCommandes;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Adresse::class)]
    private Collection $adresses;

    #[ORM\Column]
    private ?string $numero_commande = null;

    public function __construct()
    {
        $this->detailsCommandes = new ArrayCollection();
        $this->userAdressCommandes = new ArrayCollection();
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getEtatCommande(): string
    {
        return $this->etatCommande;
    }

    public function setEtatCommande(string $etatCommande): static
    {
        $this->etatCommande = $etatCommande;

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

    /**
     * @return Collection<int, DetailsCommande>
     */
    public function getDetailsCommandes(): Collection
    {
        return $this->detailsCommandes;
    }

    public function addDetailsCommande(DetailsCommande $detailsCommande): static
    {
        if (!$this->detailsCommandes->contains($detailsCommande)) {
            $this->detailsCommandes->add($detailsCommande);
            $detailsCommande->setCommande($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommande $detailsCommande): static
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getCommande() === $this) {
                $detailsCommande->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserAdressCommande>
     */
    public function getUserAdressCommandes(): Collection
    {
        return $this->userAdressCommandes;
    }

    public function addUserAdressCommande(UserAdressCommande $userAdressCommande): static
    {
        if (!$this->userAdressCommandes->contains($userAdressCommande)) {
            $this->userAdressCommandes->add($userAdressCommande);
            $userAdressCommande->setCommande($this);
        }

        return $this;
    }

    public function removeUserAdressCommande(UserAdressCommande $userAdressCommande): static
    {
        if ($this->userAdressCommandes->removeElement($userAdressCommande)) {
            // set the owning side to null (unless already changed)
            if ($userAdressCommande->getCommande() === $this) {
                $userAdressCommande->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): static
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setCommande($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): static
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getCommande() === $this) {
                $adress->setCommande(null);
            }
        }

        return $this;
    }

    public function getNumeroCommande(): ?string
    {
        return $this->numero_commande;
    }

    public function setNumeroCommande(string $numero_commande): static
    {
        $this->numero_commande = $numero_commande;

        return $this;
    }
}
