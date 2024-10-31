<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;


#[ORM\HasLifecycleCallbacks]
trait StartedAtTrait
{
    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Ignore]
    protected \DateTime $startedAt;


    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    #[ORM\PrePersist]
    public function setStartedAt($startedAt): void
    {
        $this->startedAt = new \DateTime();
    }
}