<?php

namespace App\Entity;

use App\Repository\OfferServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferServiceRepository::class)]
class OfferService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    /**
     * @var Collection<int, DemandeService>
     */
    #[ORM\OneToMany(targetEntity: DemandeService::class, mappedBy: 'offer_service_id')]
    private Collection $demandeServices;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'offer_service_id')]
    private Collection $reviews;

    #[ORM\ManyToOne(inversedBy: 'offerServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?provider $provider = null;

    #[ORM\ManyToOne(inversedBy: 'offerServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?service $service = null;

    public function __construct()
    {
        $this->demandeServices = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, DemandeService>
     */
    public function getDemandeServices(): Collection
    {
        return $this->demandeServices;
    }

    public function addDemandeService(DemandeService $demandeService): static
    {
        if (!$this->demandeServices->contains($demandeService)) {
            $this->demandeServices->add($demandeService);
            $demandeService->setOfferServiceId($this);
        }

        return $this;
    }

    public function removeDemandeService(DemandeService $demandeService): static
    {
        if ($this->demandeServices->removeElement($demandeService)) {
            // set the owning side to null (unless already changed)
            if ($demandeService->getOfferServiceId() === $this) {
                $demandeService->setOfferServiceId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setOfferServiceId($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getOfferServiceId() === $this) {
                $review->setOfferServiceId(null);
            }
        }

        return $this;
    }

    public function getProvider(): ?provider
    {
        return $this->provider;
    }

    public function setProvider(?provider $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getService(): ?service
    {
        return $this->service;
    }

    public function setService(?service $service): static
    {
        $this->service = $service;

        return $this;
    }
}
