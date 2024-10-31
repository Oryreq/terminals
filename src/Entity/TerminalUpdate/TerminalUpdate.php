<?php

namespace App\Entity\TerminalUpdate;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Terminal\Terminal;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\TerminalUpdate\TerminalUpdateRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TerminalUpdateRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    shortName: 'Update',
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'update:collection']),
    ],
    paginationEnabled: false,
)]
class TerminalUpdate
{
    use CreatedAtTrait;
    use UpdatedAtTrait;


    public array $TYPES = [
        'Modified version' => 'Модифицированная версия',
        'Stable version' => 'Стабильная версия',
    ];


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['update:collection', 'terminal:item', 'terminal:collection'])]
    private ?int $id = null;


    #[ORM\Column(nullable: true)]
    #[Groups(['update:collection'])]
    private ?string $description = null;


    #[Vich\UploadableField(mapping: 'updates', fileNameProperty: 'update')]
    private ?File $updateFile = null;


    #[ORM\Column(name: 'update_name')]
    #[Groups(['update:collection', 'terminal:item', 'terminal:collection'])]
    private ?string $update = null;


    #[ORM\Column]
    #[Assert\Type(type: 'numeric', message: 'Пожалуйста, введите номер.')]
    #[Groups(['update:collection', 'terminal:item', 'terminal:collection'])]
    private ?float $version = null;


    #[ORM\Column]
    #[Groups(['update:collection', 'terminal:item', 'terminal:collection'])]
    private ?string $type = null;


    /**
     * @var Collection<int, Terminal>
     */
    #[ORM\OneToMany(targetEntity: Terminal::class, mappedBy: 'terminalUpdate')]
    private Collection $terminals;



    public function __construct()
    {
        $this->terminals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getUpdateFile(): ?File
    {
        return $this->updateFile;
    }

    public function setUpdateFile(?File $updateFile): self
    {
        $this->updateFile = $updateFile;
        if (null !== $updateFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    public function getUpdate(): ?string
    {
        return $this->update;
    }

    public function setUpdate(?string $update): void
    {
        $this->update = $update;
    }

    public function getVersion(): ?float
    {
        return $this->version;
    }

    public function setVersion(?float $version): void
    {
        $this->version = $version;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
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
            $terminal->setTerminalUpdate($this);
        }

        return $this;
    }

    public function removeTerminal(Terminal $terminal): static
    {
        if ($this->terminals->removeElement($terminal)) {
            // set the owning side to null (unless already changed)
            if ($terminal->getTerminalUpdate() === $this) {
                $terminal->setTerminalUpdate(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->TYPES[$this->type] . ' - ' . $this->version;
    }
}