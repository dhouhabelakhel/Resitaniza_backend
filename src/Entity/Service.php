<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // /**
    //  * @var Collection<int, OfferService>
    //  */
    // #[ORM\OneToMany(targetEntity: OfferService::class, mappedBy: 'service')]
    // private Collection $offerServices;

    // public function __construct()
    // {
    //     $this->offerServices = new ArrayCollection();
    // }

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

    // /**
    //  * @return Collection<int, OfferService>
    //  */
    // public function getOfferServices(): Collection
    // {
    //     return $this->offerServices;
    // }

    // public function addOfferService(OfferService $offerService): static
    // {
    //     if (!$this->offerServices->contains($offerService)) {
    //         $this->offerServices->add($offerService);
    //         $offerService->setService($this);
    //     }

    //     return $this;
    // }

    // public function removeOfferService(OfferService $offerService): static
    // {
    //     if ($this->offerServices->removeElement($offerService)) {
    //         // set the owning side to null (unless already changed)
    //         if ($offerService->getService() === $this) {
    //             $offerService->setService(null);
    //         }
    //     }

    //     return $this;
    // }
}
