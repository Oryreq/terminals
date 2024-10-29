<?php

namespace App\Entity;

use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\RenterImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RenterImageRepository::class)]
#[Uploadable]
class RenterImage
{
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[UploadableField(mapping: 'renters_photo', fileNameProperty: 'name')]
    private ?File $file = null;

    #[ORM\Column]
    private ?string $name = null;


    #[ORM\ManyToOne(targetEntity: Renter::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Renter $renter = null;



    public function getId(): ?int
    {
        return $this->id;
    }


    public function setFile(?File $file): self
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }
    public function getFile(): ?File
    {
        return $this->file;
    }


    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): static
    {
        $this->name = $name;

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


    public function __toString(): string
    {
        return $this->getName();
    }
}