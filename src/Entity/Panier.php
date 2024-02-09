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


    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true)]
    private ?string $prixTotal = null;

    // #[ORM\OneToOne(inversedBy: 'panier', cascade: ['persist', 'remove'])]
    // private ?Clients $idClient = null;

    #[ORM\ManyToMany(targetEntity: Produits::class)]
    private Collection $listeProduits;

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

    // public function getIdClient(): ?Clients
    // {
    //     return $this->idClient;
    // }

    // public function setIdClient(?Clients $idClient): static
    // {
    //     $this->idClient = $idClient;

    //     return $this;
    // }

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
}
