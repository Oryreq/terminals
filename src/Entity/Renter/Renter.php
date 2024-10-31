<?php

namespace App\Entity\Renter;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Category\Category;
use App\Entity\Floor\Floor;
use App\Entity\Renter\Media\RenterImage;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\Renter\RenterRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RenterRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'renter:item']),
        new GetCollection(normalizationContext: ['groups' => 'renter:collection']),
    ],
    paginationEnabled: false,
)]
class Renter
{
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['renter:item', 'renter:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['renter:item', 'renter:collection'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'renters')]
    private ?Floor $floor = null;

    #[ORM\ManyToOne(inversedBy: 'renters')]
    #[Groups(['renter:item', 'renter:collection'])]
    private ?Category $category = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['renter:item', 'renter:collection'])]
    private ?string $phoneNumber = null;

    #[ORM\Column(nullable: false)]
    #[Groups(['renter:item', 'renter:collection'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    ##[Assert\Image(mimeTypes: ['image/png', 'image/jpeg', 'image/jpg'])]
    #[Groups(['renter:item', 'renter:collection'])]
    private ?string $logo = null;

    #[Vich\UploadableField(mapping: 'renters_logo', fileNameProperty: 'logo')]
    private ?File $logoFile = null;

    /**
     * @var Collection<int, RenterImage>
     */
    #[ORM\OneToMany(targetEntity: RenterImage::class, mappedBy: 'renter', cascade: ['all'])]
    #[Groups(['renter:item', 'renter:collection'])]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
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

    public function getFloor(): ?Floor
    {
        return $this->floor;
    }

    public function setFloor(?Floor $floor): static
    {
        $this->floor = $floor;

        return $this;
    }

    #[Groups(['renter:item', 'renter:collection'])]
    public function getFloorNumber(): ?int
    {
        return $this->floor->getFloorNumber();
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoFile(?File $logoFile): self
    {
        $this->logoFile = $logoFile;
        if (null !== $logoFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }


    public function __toString(): string
    {
        return $this->name ?: '';
    }

    /**
     * @return Collection<int, RenterImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(RenterImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setRenter($this);
        }

        return $this;
    }

    public function removeImage(RenterImage $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getRenter() === $this) {
                $image->setRenter(null);
            }
        }

        return $this;
    }
}
