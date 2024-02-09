<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 30)]
    private ?string $username = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?bool $account_activate = null;

    #[ORM\Column]
    private ?bool $client_activate = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FormulaireDemandeProduit::class)]
    private Collection $liste_formulaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Adresses::class)]
    private Collection $list_adresses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commandes::class)]
    private Collection $list_commandes;

    public function __construct()
    {
        $this->liste_formulaires = new ArrayCollection();
        $this->list_adresses = new ArrayCollection();
        $this->list_commandes = new ArrayCollection();
    }



    // #[ORM\OneToOne(targetEntity : "Clients", mappedBy: 'user', cascade: ['persist', 'remove'])]
    
    // protected ?Clients $clients = null;

    // #[ORM\OneToOne(targetEntity : "Vendeurs", mappedBy: 'userVendeur', cascade: ['persist', 'remove'])]
    // private ?Vendeurs $vendeurs = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    // public function getClients(): ?Clients
    // {
    //     return $this->clients;
    // }

    // public function setClients(Clients $clients): static
    // {
    //     // set the owning side of the relation if necessary
    //     if ($clients->getUser() !== $this) {
    //         $clients->setUser($this);
    //     }

    //     $this->clients = $clients;

    //     return $this;
    // }

    // public function getVendeurs(): ?Vendeurs
    // {
    //     return $this->vendeurs;
    // }

    // public function setVendeurs(Vendeurs $vendeurs): static
    // {
    //     // set the owning side of the relation if necessary
    //     if ($vendeurs->getUserVendeur() !== $this) {
    //         $vendeurs->setUserVendeur($this);
    //     }

    //     $this->vendeurs = $vendeurs;

    //     return $this;
    // }

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

    public function isAccountActivate(): ?bool
    {
        return $this->account_activate;
    }

    public function setAccountActivate(bool $account_activate): static
    {
        $this->account_activate = $account_activate;

        return $this;
    }

    public function isClientActivate(): ?bool
    {
        return $this->client_activate;
    }

    public function setClientActivate(bool $client_activate): static
    {
        $this->client_activate = $client_activate;

        return $this;
    }

    /**
     * @return Collection<int, FormulaireDemandeProduit>
     */
    public function getListeFormulaires(): Collection
    {
        return $this->liste_formulaires;
    }

    public function addListeFormulaire(FormulaireDemandeProduit $listeFormulaire): static
    {
        if (!$this->liste_formulaires->contains($listeFormulaire)) {
            $this->liste_formulaires->add($listeFormulaire);
            $listeFormulaire->setUser($this);
        }

        return $this;
    }

    public function removeListeFormulaire(FormulaireDemandeProduit $listeFormulaire): static
    {
        if ($this->liste_formulaires->removeElement($listeFormulaire)) {
            // set the owning side to null (unless already changed)
            if ($listeFormulaire->getUser() === $this) {
                $listeFormulaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adresses>
     */
    public function getListAdresses(): Collection
    {
        return $this->list_adresses;
    }

    public function addListAdress(Adresses $listAdress): static
    {
        if (!$this->list_adresses->contains($listAdress)) {
            $this->list_adresses->add($listAdress);
            $listAdress->setUser($this);
        }

        return $this;
    }

    public function removeListAdress(Adresses $listAdress): static
    {
        if ($this->list_adresses->removeElement($listAdress)) {
            // set the owning side to null (unless already changed)
            if ($listAdress->getUser() === $this) {
                $listAdress->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commandes>
     */
    public function getListCommandes(): Collection
    {
        return $this->list_commandes;
    }

    public function addListCommande(Commandes $listCommande): static
    {
        if (!$this->list_commandes->contains($listCommande)) {
            $this->list_commandes->add($listCommande);
            $listCommande->setUser($this);
        }

        return $this;
    }

    public function removeListCommande(Commandes $listCommande): static
    {
        if ($this->list_commandes->removeElement($listCommande)) {
            // set the owning side to null (unless already changed)
            if ($listCommande->getUser() === $this) {
                $listCommande->setUser(null);
            }
        }

        return $this;
    }

}
