<?php

namespace App\Entity;


use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\TerminalUpdateRepository;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TerminalUpdateRepository::class)]
#[Vich\Uploadable]
class TerminalUpdate
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'updates', fileNameProperty: 'update')]
    private ?File $updateFile = null;

    #[ORM\Column(name: 'update_name')]
    private ?string $update = null;

    #[ORM\Column]
    private ?string $version = null;

    #[ORM\Column]
    private ?string $type = null;



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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): void
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
}