<?php

namespace App\Entity;

use App\Repository\MangerResidenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MangerResidenceRepository::class)]
class MangerResidence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $ownersince = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $leftat = null;

    #[ORM\ManyToOne(inversedBy: 'mangerResidences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?propertymanger $manager = null;

    #[ORM\ManyToOne(inversedBy: 'mangerResidences')]
    private ?residence $residence_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwnersince(): ?\DateTimeInterface
    {
        return $this->ownersince;
    }

    public function setOwnersince(\DateTimeInterface $ownersince): static
    {
        $this->ownersince = $ownersince;

        return $this;
    }

    public function getLeftat(): ?\DateTimeInterface
    {
        return $this->leftat;
    }

    public function setLeftat(\DateTimeInterface $leftat): static
    {
        $this->leftat = $leftat;

        return $this;
    }

    public function getManagerId(): ?propertymanger
    {
        return $this->manager;
    }

    public function setManagerId(?propertymanger $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    public function getResidenceId(): ?residence
    {
        return $this->residence_id;
    }

    public function setResidenceId(?residence $residence_id): static
    {
        $this->residence_id = $residence_id;

        return $this;
    }
}
