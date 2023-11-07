<?php

namespace App\Entity;

use App\Repository\AdressesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdressesRepository::class)]
class Adresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4)]
    private ?string $numAdrss = null;

    #[ORM\Column(length: 50)]
    private ?string $rueAdrss = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $complementAdrss = null;

    #[ORM\Column(length: 30)]
    private ?string $villeAdrss = null;

    #[ORM\Column(length: 8)]
    private ?string $codePostalAdrss = null;

    #[ORM\Column(length: 30)]
    private ?string $paysAdrss = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false, name: 'idClientAdresse')]
    private ?Clients $idClientAdresses = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumAdrss(): ?string
    {
        return $this->numAdrss;
    }

    public function setNumAdrss(string $numAdrss): static
    {
        $this->numAdrss = $numAdrss;

        return $this;
    }

    public function getRueAdrss(): ?string
    {
        return $this->rueAdrss;
    }

    public function setRueAdrss(string $rueAdrss): static
    {
        $this->rueAdrss = $rueAdrss;

        return $this;
    }

    public function getComplementAdrss(): ?string
    {
        return $this->complementAdrss;
    }

    public function setComplementAdrss(?string $complementAdrss): static
    {
        $this->complementAdrss = $complementAdrss;

        return $this;
    }

    public function getVilleAdrss(): ?string
    {
        return $this->villeAdrss;
    }

    public function setVilleAdrss(string $villeAdrss): static
    {
        $this->villeAdrss = $villeAdrss;

        return $this;
    }

    public function getCodePostalAdrss(): ?string
    {
        return $this->codePostalAdrss;
    }

    public function setCodePostalAdrss(string $codePostalAdrss): static
    {
        $this->codePostalAdrss = $codePostalAdrss;

        return $this;
    }

    public function getPaysAdrss(): ?string
    {
        return $this->paysAdrss;
    }

    public function setPaysAdrss(string $paysAdrss): static
    {
        $this->paysAdrss = $paysAdrss;

        return $this;
    }

    public function getIdClientAdresses(): ?Clients
    {
        return $this->idClientAdresses;
    }

    public function setIdClientAdresses(?Clients $idClientAdresses): static
    {
        $this->idClientAdresses = $idClientAdresses;

        return $this;
    }
}
