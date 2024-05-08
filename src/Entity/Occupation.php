<?php

namespace App\Entity;

use App\Repository\OccupationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OccupationRepository::class)]
class Occupation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    /**
     * @var Collection<int, resident>
     */
    #[ORM\ManyToMany(targetEntity: resident::class, inversedBy: 'occupations')]
    private Collection $resident_id;

    #[ORM\ManyToOne(inversedBy: 'occupations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?appartment $appartement_id = null;

    public function __construct()
    {
        $this->resident_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, resident>
     */
    public function getResidentId(): Collection
    {
        return $this->resident_id;
    }

    public function addResidentId(resident $residentId): static
    {
        if (!$this->resident_id->contains($residentId)) {
            $this->resident_id->add($residentId);
        }

        return $this;
    }

    public function removeResidentId(resident $residentId): static
    {
        $this->resident_id->removeElement($residentId);

        return $this;
    }

    public function getAppartementId(): ?appartment
    {
        return $this->appartement_id;
    }

    public function setAppartementId(?appartment $appartement_id): static
    {
        $this->appartement_id = $appartement_id;

        return $this;
    }
}
