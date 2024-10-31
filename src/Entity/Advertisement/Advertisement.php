<?php

namespace App\Entity\Advertisement;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Advertisement\Form\AdvertisementProperty;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\Advertisement\AdvertisementRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: AdvertisementRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'advertisement:item']),
        new GetCollection(normalizationContext: ['groups' => 'advertisement:item']),
    ],
    paginationEnabled: false,
)]
class Advertisement
{
    use UpdatedAtTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $advertisement = null;


    #[Vich\UploadableField(mapping: 'advertisement', fileNameProperty: 'advertisement')]
    private ?File $advertisementFile = null;


    #[ORM\Column]
    private ?bool $canShow = null;


    /**
     * @var Collection<int, AdvertisementProperty>
     */
    #[ORM\OneToMany(targetEntity: AdvertisementProperty::class, mappedBy: 'advertisement', cascade: ['persist', 'remove'])]
    private Collection $properties;



    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdvertisement(): ?string
    {
        return $this->advertisement;
    }

    public function setAdvertisement(?string $advertisement): void
    {
        $this->advertisement = $advertisement;
    }

    public function getAdvertisementFile(): ?File
    {
        return $this->advertisementFile;
    }

    public function setAdvertisementFile(?File $advertisementFile): self
    {
        $this->advertisementFile = $advertisementFile;
        if (null !== $advertisementFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    public function getCanShow(): ?bool
    {
        return $this->canShow;
    }

    public function setCanShow(?bool $canShow): void
    {
        $this->canShow = $canShow;
    }

    /**
     * @return Collection<int, AdvertisementProperty>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(AdvertisementProperty $property): static
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
            $property->setAdvertisement($this);
        }

        return $this;
    }

    public function removeProperty(AdvertisementProperty $property): static
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getAdvertisement() === $this) {
                $property->setAdvertisement(null);
            }
        }

        return $this;
    }

    public function propertiesToString(): string
    {
        $result = '';
        for ($i = 0; $i < $this->properties->count(); $i++) {
            $result .= ($i + 1) . ') ' . $this->properties->get($i) . PHP_EOL;
        }
        return $result;
    }



}