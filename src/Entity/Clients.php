<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients   /* extends User*/
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity : "User", inversedBy: 'clients', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, name:"user_id", referencedColumnName:"user_id")] // la réference est la pour relier a la colum de user
    protected ?user $user = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'refClient', targetEntity: FormulaireDemandeProduit::class)]
    private Collection $formEnvoyer;

    public function __construct()
    {
        $this->formEnvoyer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, FormulaireDemandeProduit>
     */
    public function getFormEnvoyer(): Collection
    {
        return $this->formEnvoyer;
    }

    public function addFormEnvoyer(FormulaireDemandeProduit $formEnvoyer): static
    {
        if (!$this->formEnvoyer->contains($formEnvoyer)) {
            $this->formEnvoyer->add($formEnvoyer);
            $formEnvoyer->setRefClient($this);
        }

        return $this;
    }

    public function removeFormEnvoyer(FormulaireDemandeProduit $formEnvoyer): static
    {
        if ($this->formEnvoyer->removeElement($formEnvoyer)) {
            // set the owning side to null (unless already changed)
            if ($formEnvoyer->getRefClient() === $this) {
                $formEnvoyer->setRefClient(null);
            }
        }

        return $this;
    }
}
