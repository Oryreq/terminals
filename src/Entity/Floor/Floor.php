<?php

namespace App\Entity\Floor;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Renter\Renter;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\Floor\FloorRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: FloorRepository::class)]
#[UniqueEntity('floorNumber')]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'floor:item']),
        new GetCollection(normalizationContext: ['groups' => 'floor:collection']),
    ],
    paginationEnabled: false,
)]
class Floor
{
    use CreatedAtTrait;
    use UpdatedAtTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['floor:item', 'floor:collection'])]
    private ?int $id = null;


    #[ORM\Column(unique: true)]
    #[Groups(['floor:item', 'floor:collection', 'category:item', 'category:collection'])]
    private ?int $floorNumber = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['floor:item', 'floor:collection'])]
    private ?string $image = null;


    #[Vich\UploadableField(mapping: 'floors', fileNameProperty: 'image')]
    private ?File $imageFile = null;


    /**
     * @var Collection<int, Renter>
     */
    #[ORM\OneToMany(targetEntity: Renter::class, mappedBy: 'floor', cascade: ['all'])]
    private Collection $renters;



    public function __construct()
    {
        $this->renters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFloorNumber(): ?int
    {
        return $this->floorNumber;
    }

    public function setFloorNumber(?int $floorNumber): static
    {
        $this->floorNumber = $floorNumber;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    /**
     * @return Collection<int, Renter>
     */
    public function getRenters(): Collection
    {
        return $this->renters;
    }

    public function addRenter(Renter $renter): static
    {
        if (!$this->renters->contains($renter)) {
            $this->renters->add($renter);
            $renter->setFloor($this);
        }

        return $this;
    }

    public function removeRenter(Renter $renter): static
    {
        if ($this->renters->removeElement($renter)) {
            // set the owning side to null (unless already changed)
            if ($renter->getFloor() === $this) {
                $renter->setFloor(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->floorNumber;
    }
}
