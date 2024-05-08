<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $stars = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?resident $resident_id = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?offerservice $offer_service_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(int $stars): static
    {
        $this->stars = $stars;

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