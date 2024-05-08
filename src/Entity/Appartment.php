<?php

namespace App\Entity;

use App\Repository\AppartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppartmentRepository::class)]
class Appartment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column]
    private ?bool $rent = null;

    #[ORM\ManyToOne(inversedBy: 'appartments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?bloc $bloc_id = null;

    /**
     * @var Collection<int, Occupation>
     */
    #[ORM\OneToMany(targetEntity: Occupation::class, mappedBy: 'appartement_id')]
    private Collection $occupations;

    public function __construct()
    {
        $this->occupations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function isRent(): ?bool
    {
        return $this->rent;
    }

    public function setRent(bool $rent): static
    {
        $this->rent = $rent;

        return $this;
    }

    public function getBlocId(): ?bloc
    {
        return $this->bloc_id;
    }

    public function setBlocId(?bloc $bloc_id): static
    {
        $this->bloc_id = $bloc_id;

        return $this;
    }

    /**
     * @return Collection<int, Occupation>
     */
    public function getOccupations(): Collection
    {
        return $this->occupations;
    }

    public function addOccupation(Occupation $occupation): static
    {
        if (!$this->occupations->contains($occupation)) {
            $this->occupations->add($occupation);
            $occupation->setAppartementId($this);
        }

        return $this;
    }

    public function removeOccupation(Occupation $occupation): static
    {
        if ($this->occupations->removeElement($occupation)) {
            // set the owning side to null (unless already changed)
            if ($occupation->getAppartementId() === $this) {
                $occupation->setAppartementId(null);
            }
        }

        return $this;
    }
}
