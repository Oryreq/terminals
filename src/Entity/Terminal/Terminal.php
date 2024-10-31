<?php

namespace App\Entity\Terminal;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Advertisement\Form\AdvertisementProperty;
use App\Entity\TerminalUpdate\TerminalUpdate;
use App\Repository\Terminal\TerminalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: TerminalRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'terminal:item']),
        new GetCollection(normalizationContext: ['groups' => 'terminal:collection']),
    ],
    paginationEnabled: false,
)]
class Terminal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['terminal:item', 'terminal:collection'])]

    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups(['terminal:item', 'terminal:collection'])]
    private ?string $name = null;


    #[ORM\Column]
    #[Groups(['terminal:item', 'terminal:collection'])]
    private ?int $sleepDelay = null;


    #[ORM\Column]
    #[Groups(['terminal:item', 'terminal:collection'])]
    private ?int $changingAdvertisementTime = null;


    #[ORM\ManyToOne(inversedBy: 'terminals')]
    #[Groups(['terminal:item', 'terminal:collection'])]
    private ?TerminalUpdate $terminalUpdate = null;


    /**
     * @var Collection<int, AdvertisementProperty>
     */
    #[ORM\ManyToMany(targetEntity: AdvertisementProperty::class, mappedBy: 'terminals')]
    private Collection $advertisementProperties;



    public function __construct()
    {
        $this->advertisementProperties = new ArrayCollection();
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

    public function getSleepDelay(): ?int
    {
        return $this->sleepDelay;
    }

    public function setSleepDelay(int $sleepDelay): static
    {
        $this->sleepDelay = $sleepDelay;

        return $this;
    }

    public function getChangingAdvertisementTime(): ?int
    {
        return $this->changingAdvertisementTime;
    }

    public function setChangingAdvertisementTime(int $changingAdvertisementTime): static
    {
        $this->changingAdvertisementTime = $changingAdvertisementTime;

        return $this;
    }

    public function getTerminalUpdate(): ?TerminalUpdate
    {
        return $this->terminalUpdate;
    }

    public function setTerminalUpdate(?TerminalUpdate $terminalUpdate): static
    {
        $this->terminalUpdate = $terminalUpdate;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, AdvertisementProperty>
     */
    public function getAdvertisementProperties(): Collection
    {
        return $this->advertisementProperties;
    }

    public function addAdvertisementProperty(AdvertisementProperty $advertisementProperty): static
    {
        if (!$this->advertisementProperties->contains($advertisementProperty)) {
            $this->advertisementProperties->add($advertisementProperty);
            $advertisementProperty->addTerminal($this);
        }

        return $this;
    }

    public function removeAdvertisementProperty(AdvertisementProperty $advertisementProperty): static
    {
        if ($this->advertisementProperties->removeElement($advertisementProperty)) {
            $advertisementProperty->removeTerminal($this);
        }

        return $this;
    }

    public function advertisementsToString(): string
    {
        $result = '';
        for ($i = 0; $i < $this->advertisementProperties->count(); $i++) {
            $result .= $this->advertisementProperties->get($i) . PHP_EOL;
        }
        return $result;
    }
}
