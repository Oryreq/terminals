<?php

namespace App\Entity;

use App\Entity\Traits\EndedAtTrait;
use App\Entity\Traits\StartedAtTrait;
use App\Repository\AdvertisementPropertyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: AdvertisementPropertyRepository::class)]
class AdvertisementProperty
{
    use StartedAtTrait;
    use EndedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $displayOrder = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    private ?Advertisement $advertisement = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): static
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    public function getAdvertisement(): ?Advertisement
    {
        return $this->advertisement;
    }

    public function setAdvertisement(?Advertisement $advertisement): static
    {
        $this->advertisement = $advertisement;

        return $this;
    }

}
