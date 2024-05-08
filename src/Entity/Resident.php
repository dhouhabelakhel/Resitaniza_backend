<?php

namespace App\Entity;

use App\Repository\ResidentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ResidentRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_cin', fields: ['cin'])]

class Resident implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

  
    #[ORM\Column]
    private array $roles = ['Resident'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?int $cin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    private ?string $phonenumber = null;

    // /**
    //  * @var Collection<int, Occupation>
    //  */
    // #[ORM\ManyToMany(targetEntity: Occupation::class, mappedBy: 'resident_id')]
    // private Collection $occupations;

    // /**
    //  * @var Collection<int, DemandeService>
    //  */
    // #[ORM\OneToMany(targetEntity: DemandeService::class, mappedBy: 'resident_id', orphanRemoval: true)]
    // private Collection $demandeServices;

    // /**
    //  * @var Collection<int, Review>
    //  */
    // #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'resident_id', orphanRemoval: true)]
    // private Collection $reviews;

    // public function __construct()
    // {
    //     $this->occupations = new ArrayCollection();
    //     $this->demandeServices = new ArrayCollection();
    //     $this->reviews = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }
public Function setId(int $id){
$this->id=$id;
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
       return $this->roles;
       
    }
    public function setRole(string $role)
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    // /**
    //  * @return Collection<int, Occupation>
    //  */
    // public function getOccupations(): Collection
    // {
    //     return $this->occupations;
    // }

    // public function addOccupation(Occupation $occupation): static
    // {
    //     if (!$this->occupations->contains($occupation)) {
    //         $this->occupations->add($occupation);
    //         $occupation->addResidentId($this);
    //     }

    //     return $this;
    // }

    // public function removeOccupation(Occupation $occupation): static
    // {
    //     if ($this->occupations->removeElement($occupation)) {
    //         $occupation->removeResidentId($this);
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, DemandeService>
    //  */
    // public function getDemandeServices(): Collection
    // {
    //     return $this->demandeServices;
    // }

    // public function addDemandeService(DemandeService $demandeService): static
    // {
    //     if (!$this->demandeServices->contains($demandeService)) {
    //         $this->demandeServices->add($demandeService);
    //         $demandeService->setResidentId($this);
    //     }

    //     return $this;
    // }

    // public function removeDemandeService(DemandeService $demandeService): static
    // {
    //     if ($this->demandeServices->removeElement($demandeService)) {
    //         // set the owning side to null (unless already changed)
    //         if ($demandeService->getResidentId() === $this) {
    //             $demandeService->setResidentId(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Review>
    //  */
    // public function getReviews(): Collection
    // {
    //     return $this->reviews;
    // }

    // public function addReview(Review $review): static
    // {
    //     if (!$this->reviews->contains($review)) {
    //         $this->reviews->add($review);
    //         $review->setResidentId($this);
    //     }

    //     return $this;
    // }

    // public function removeReview(Review $review): static
    // {
    //     if ($this->reviews->removeElement($review)) {
    //         // set the owning side to null (unless already changed)
    //         if ($review->getResidentId() === $this) {
    //             $review->setResidentId(null);
    //         }
    //     }

    //     return $this;
    // }
}
