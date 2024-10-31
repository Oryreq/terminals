<?php

namespace App\Entity\Renter\Media;

use App\Entity\Renter\Renter;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\Renter\RenterImageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RenterImageRepository::class)]
#[Vich\Uploadable]
class RenterImage
{
    use UpdatedAtTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['renter:item', 'category:item'])]
    private ?int $id = null;


    #[ORM\Column]
    #[Groups(['renter:item', 'category:item'])]
    private ?string $image = null;


    #[Vich\UploadableField(mapping: 'renters_photo', fileNameProperty: 'image')]
    private ?File $imageFile = null;


    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Renter $renter = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getRenter(): ?Renter
    {
        return $this->renter;
    }

    public function setRenter(?Renter $renter): static
    {
        $this->renter = $renter;

        return $this;
    }
}