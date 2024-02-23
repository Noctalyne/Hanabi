<?php

namespace App\Entity;

use App\Repository\FormulaireDemandeProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormulaireDemandeProduitRepository::class)]
class FormulaireDemandeProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $typeProduit = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $autresTypes = null;
    
    #[ORM\Column(length: 300)]
    private ?string $descriptionProduit = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    // private ?\DateTimeInterface $dateEnvoieForm = null;
    private $dateEnvoieForm;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    // private ?\DateTimeInterface $dateReponseForm = null;
    private $dateReponseForm;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $reponseDemande = null;


    #[ORM\ManyToOne(inversedBy: 'liste_formulaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeProduit(): ?string
    {
        return $this->typeProduit;
    }

    public function setTypeProduit(string $typeProduit): static
    {
        $this->typeProduit = $typeProduit;

        return $this;
    }

    public function getAutresTypes(): ?string
    {
        return $this->autresTypes;
    }

    public function setAutresTypes(?string $autresTypes): static
    {
        $this->autresTypes = $autresTypes;

        return $this;
    }

    public function getDescriptionProduit(): ?string
    {
        return $this->descriptionProduit;
    }

    public function setDescriptionProduit(?string $descriptionProduit): static
    {
        $this->descriptionProduit = $descriptionProduit;

        return $this;
    }

    public function getDateEnvoieForm(): ?\DateTimeInterface
    {
        return $this->dateEnvoieForm;
    }

    public function setDateEnvoieForm(\DateTimeInterface $dateEnvoieForm): static
    {
        $this->dateEnvoieForm = $dateEnvoieForm;

        return $this;
    }

    public function getDateReponseForm(): ?\DateTimeInterface
    {
        return $this->dateReponseForm;
    }

    public function setDateReponseForm(?\DateTimeInterface $dateReponseForm): static
    {
        $this->dateReponseForm = $dateReponseForm;

        return $this;
    }

    public function getReponseDemande(): ?string 
    {
        return $this->reponseDemande;
    }

    public function setReponseDemande(?string $reponseDemande): static
    {
        $this->reponseDemande = $reponseDemande;

        return $this;
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

}
