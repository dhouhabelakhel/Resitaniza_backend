<?php

namespace App\Entity;

use App\Repository\BlocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocRepository::class)]
class Bloc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

   

    #[ORM\ManyToOne(inversedBy: 'blocs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?residence $residence = null;

    /**
     * @var Collection<int, Appartment>
     */
    #[ORM\OneToMany(targetEntity: Appartment::class, mappedBy: 'bloc_id', orphanRemoval: true)]
    private Collection $appartments;

    #[ORM\Column]
    private ?int $etage = null;

    public function __construct()
    {
        $this->appartments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

  


    public function getResidenceId(): ?residence
    {
        return $this->residence;
    }

    public function setResidenceId(?residence $residence): static
    {
        $this->residence = $residence;

        return $this;
    }

    /**
     * @return Collection<int, Appartment>
     */
    public function getAppartments(): Collection
    {
        return $this->appartments;
    }

    public function addAppartment(Appartment $appartment): static
    {
        if (!$this->appartments->contains($appartment)) {
            $this->appartments->add($appartment);
            $appartment->setBlocId($this);
        }

        return $this;
    }

    public function removeAppartment(Appartment $appartment): static
    {
        if ($this->appartments->removeElement($appartment)) {
            // set the owning side to null (unless already changed)
            if ($appartment->getBlocId() === $this) {
                $appartment->setBlocId(null);
            }
        }

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->etage;
    }

    public function setFloor(int $etage): static
    {
        $this->etage = $etage;

        return $this;
    }
}
