<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true)]
    private ?string $prixTotal = null;


    #[ORM\ManyToMany(targetEntity: Produits::class)]
    private Collection $listeProduits;

    #[ORM\OneToOne(mappedBy: 'panier', cascade: ['persist', 'remove'])]
    private ?User $user = null;



    public function __construct()
    {
        $this->listeProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(?string $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }


    /**
     * @return Collection<int, Produits>
     */
    public function getListeProduits(): Collection
    {
        return $this->listeProduits;
    }

    public function addListeProduit(Produits $listeProduit): static
    {
        if (!$this->listeProduits->contains($listeProduit)) {
            $this->listeProduits->add($listeProduit);
        }

        return $this;
    }

    public function removeListeProduit(Produits $listeProduit): static
    {
        $this->listeProduits->removeElement($listeProduit);

        return $this;
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


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setPanier(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getPanier() !== $this) {
            $user->setPanier($this);
        }

        $this->user = $user;

        return $this;
    }

}
