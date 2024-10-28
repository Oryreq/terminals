<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\FloorRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
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
    #[Groups(['floor:item', 'floor:collection'])]
    private ?string $floorNumber = null;


    #[Vich\UploadableField(mapping: 'floors', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['floor:item', 'floor:collection'])]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFloorNumber(): ?string
    {
        return $this->floorNumber;
    }

    public function setFloorNumber(string $floorNumber): static
    {
        $this->floorNumber = $floorNumber;

        return $this;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
}
