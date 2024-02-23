<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column]
    private array $listeProduits = [];

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $prixTotal = null;

    // #[ORM\ManyToMany(targetEntity: Clients::class, inversedBy: 'idClientCommande')]
    // private Collection $client;

    #[ORM\ManyToOne(inversedBy: 'list_commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?string $transporteur = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $prixTransport = null;

    #[ORM\Column]
    private ?bool $succesPaiement = null;

    #[ORM\Column(length: 255)]
    private ?string $idTransaction = null;

    // public function __construct()
    // {
    //     $this->client = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getListeProduits(): array
    {
        return $this->listeProduits;
    }

    public function setListeProduits(array $listeProduits): static
    {
        $this->listeProduits = $listeProduits;

        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(string $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    // /**
    //  * @return Collection<int, Clients>
    //  */
    // public function getClient(): Collection
    // {
    //     return $this->client;
    // }

    // public function addClient(Clients $client): static
    // {
    //     if (!$this->client->contains($client)) {
    //         $this->client->add($client);
    //     }

    //     return $this;
    // }

    // public function removeClient(Clients $client): static
    // {
    //     $this->client->removeElement($client);

    //     return $this;
    // }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTransporteur(): ?string
    {
        return $this->transporteur;
    }

    public function setTransporteur(string $transporteur): static
    {
        $this->transporteur = $transporteur;

        return $this;
    }

    public function getPrixTransport(): ?string
    {
        return $this->prixTransport;
    }

    public function setPrixTransport(string $prixTransport): static
    {
        $this->prixTransport = $prixTransport;

        return $this;
    }

    public function isSuccesPaiement(): ?bool
    {
        return $this->succesPaiement;
    }

    public function setSuccesPaiement(bool $succesPaiement): static
    {
        $this->succesPaiement = $succesPaiement;

        return $this;
    }

    public function getIdTransaction(): ?string
    {
        return $this->idTransaction;
    }

    public function setIdTransaction(string $idTransaction): static
    {
        $this->idTransaction = $idTransaction;

        return $this;
    }
}
