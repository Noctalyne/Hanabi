<?php

namespace App\Entity;

use App\Repository\BanniereRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BanniereRepository::class)]
class Banniere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
  #[ORM\Column(length: 100)]
    private ?string $nomBanniere = null;
    
    #[ORM\Column(length: 100)]
    private ?string $premiereImage = null;

    #[ORM\Column(length: 100)]
    private ?string $deuxiemeImage = null;

    #[ORM\Column(length: 100)]
    private ?string $troisiemeImage = null;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPremiereImage(): ?string
    {
        return $this->premiereImage;
    }

    public function setPremiereImage(string $premiereImage): static
    {
        $this->premiereImage = $premiereImage;

        return $this;
    }

    public function getDeuxiemeImage(): ?string
    {
        return $this->deuxiemeImage;
    }

    public function setDeuxiemeImage(string $deuxiemeImage): static
    {
        $this->deuxiemeImage = $deuxiemeImage;

        return $this;
    }

    public function getTroisiemeImage(): ?string
    {
        return $this->troisiemeImage;
    }

    public function setTroisiemeImage(string $troisiemeImage): static
    {
        $this->troisiemeImage = $troisiemeImage;

        return $this;
    }

    public function getNomBanniere(): ?string
    {
        return $this->nomBanniere;
    }

    public function setNomBanniere(string $nomBanniere): static
    {
        $this->nomBanniere = $nomBanniere;

        return $this;
    }
}
