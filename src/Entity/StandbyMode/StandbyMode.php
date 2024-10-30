<?php

namespace App\Entity\StandbyMode;

use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\StandbyMode\StandbyModeRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: StandbyModeRepository::class)]
#[Vich\Uploadable]
class StandbyMode
{
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mode = null;

    #[Vich\UploadableField(mapping: 'standby_mode', fileNameProperty: 'mode')]
    private ?File $modeFile = null;

    #[ORM\Column]
    private ?bool $isVisible = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    public function getModeFile(): ?File
    {
        return $this->modeFile;
    }

    public function setModeFile(?File $modeFile): self
    {
        $this->modeFile = $modeFile;
        if (null !== $modeFile) {
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(?bool $isVisible): void
    {
        $this->isVisible = $isVisible;
    }
}