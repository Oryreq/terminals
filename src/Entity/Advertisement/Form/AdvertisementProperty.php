<?php

namespace App\Entity\Advertisement\Form;

use App\Entity\Advertisement\Advertisement;
use App\Entity\Terminal\Terminal;
use App\Entity\Traits\EndedAtTrait;
use App\Entity\Traits\StartedAtTrait;
use App\Repository\Advertisement\AdvertisementPropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Terminal>
     */
    #[ORM\ManyToMany(targetEntity: Terminal::class, inversedBy: 'advertisementProperties')]
    private Collection $terminals;

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


    public function __toString(): string
    {
        return 'Дата начала - '. $this->getStartedAt()->format('d-m-Y, H:i') . PHP_EOL .
               'Дата конца - ' . $this->getEndedAt()->format('d-m-Y, H:i') . PHP_EOL .
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
}
