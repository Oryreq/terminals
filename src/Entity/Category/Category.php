<?php

namespace App\Entity\Category;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Renter\Renter;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\Category\CategoryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[Vich\Uploadable]
#[ApiResource]
#[UniqueEntity('name')]
class Category
{
    use UpdatedAtTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $name = null;


    #[ORM\Column(name: 'image_name')]
    private ?string $image = null;


    #[Vich\UploadableField(mapping: 'categories', fileNameProperty: 'image')]
    #[Assert\Image(mimeTypes: ['image/png', 'image/jpeg', 'image/jpg'])]
    private ?File $imageFile = null;


    /**
     * @var Collection<int, Renter>
     */
    #[ORM\OneToMany(targetEntity: Renter::class, mappedBy: 'category')]
    private Collection $renters;



    public function __construct()
    {
        $this->renters = new ArrayCollection();
    }

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
            $renter->setCategory($this);
        }

        return $this;
    }

    public function removeRenter(Renter $renter): static
    {
        if ($this->renters->removeElement($renter)) {
            // set the owning side to null (unless already changed)
            if ($renter->getCategory() === $this) {
                $renter->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
