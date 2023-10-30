<?php

namespace App\Entity;

use App\Repository\VendeursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VendeursRepository::class)]
class Vendeurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity : "User", inversedBy: 'vendeurs', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, name:"vendeur_user_id", referencedColumnName:"user_id")] // la réference est la pour relier a la colum de user
    private ?User $userVendeur = null;

    #[ORM\Column(length: 30)]
    private ?string $Nom = null;

    #[ORM\Column(length: 30)]
    private ?string $prenom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserVendeur(): ?User
    {
        return $this->userVendeur;
    }

    public function setUserVendeur(User $userVendeur): static
    {
        $this->userVendeur = $userVendeur;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }
}
