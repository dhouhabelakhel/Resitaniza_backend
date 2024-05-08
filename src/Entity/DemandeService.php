<?php

namespace App\Entity;

use App\Repository\DemandeServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeServiceRepository::class)]
class DemandeService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?bool $confirmed = null;

    #[ORM\ManyToOne(inversedBy: 'demandeServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?resident $resident_id = null;

    #[ORM\ManyToOne(inversedBy: 'demandeServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?offerservice $offer_service_id = null;

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

    public function isConfirmed(): ?bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): static
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function getResidentId(): ?resident
    {
        return $this->resident_id;
    }

    public function setResidentId(?resident $resident_id): static
    {
        $this->resident_id = $resident_id;

        return $this;
    }

    public function getOfferServiceId(): ?offerservice
    {
        return $this->offer_service_id;
    }

    public function setOfferServiceId(?offerservice $offer_service_id): static
    {
        $this->offer_service_id = $offer_service_id;

        return $this;
    }
}
