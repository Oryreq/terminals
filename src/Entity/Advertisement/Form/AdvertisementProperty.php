<?php

namespace App\Entity\Advertisement\Form;

use App\Entity\Advertisement\Advertisement;
use App\Entity\Terminal\Terminal;
use App\Repository\Advertisement\AdvertisementPropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: AdvertisementPropertyRepository::class)]
#[Uploadable]
class AdvertisementProperty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['advertisement:item'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['advertisement:item'])]
    private ?int $displayOrder = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    private ?Advertisement $advertisement = null;

    /**
     * @var Collection<int, Terminal>
     */
    #[ORM\ManyToMany(targetEntity: Terminal::class, inversedBy: 'advertisementProperties')]
    private Collection $terminals;


    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Groups(['advertisement:item'])]
    private \DateTime $startAt;


    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Groups(['advertisement:item'])]
    private \DateTime $endAt;


    public function __construct()
    {
        $this->terminals = new ArrayCollection();
    }

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

    #[Groups(['advertisement:item'])]
    public function getAdvertisementImage(): ?string
    {
        return $this->advertisement->getAdvertisement();
    }

    public function __toString(): string
    {
        return 'Дата начала - '. $this->getStartAt()->format('d-m-Y, H:i') . PHP_EOL .
               'Дата конца - ' . $this->getEndAt()->format('d-m-Y, H:i') . PHP_EOL .
               'Порядок отображения - ' . $this->getDisplayOrder();
    }

    /**
     * @return Collection<int, Terminal>
     */
    public function getTerminals(): Collection
    {
        return $this->terminals;
    }

    public function addTerminal(Terminal $terminal): static
    {
        if (!$this->terminals->contains($terminal)) {
            $this->terminals->add($terminal);
        }

        return $this;
    }

    public function removeTerminal(Terminal $terminal): static
    {
        $this->terminals->removeElement($terminal);

        return $this;
    }

    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    public function setStartAt($startAt): static
    {
        $this->startAt = $startAt ?: new \DateTime();

        return $this;
    }

    public function getEndAt(): \DateTime
    {
        return $this->endAt;

    }

    public function setEndAt($endAt): static
    {
        $this->endAt = $endAt ?: new \DateTime();

        return $this;
    }
}
