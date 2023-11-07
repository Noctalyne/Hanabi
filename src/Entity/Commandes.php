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

    #[ORM\ManyToMany(targetEntity: Clients::class, inversedBy: 'idClientCommande')]
    private Collection $client;

    public function __construct()
    {
        $this->client = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Clients>
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(Clients $client): static
    {
        if (!$this->client->contains($client)) {
            $this->client->add($client);
        }

        return $this;
    }

    public function removeClient(Clients $client): static
    {
        $this->client->removeElement($client);

        return $this;
    }
}
