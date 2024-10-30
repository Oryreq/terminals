<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;


#[ORM\HasLifecycleCallbacks]
trait EndedAtTrait
{
    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Ignore]
    protected \DateTime $endedAt;

    public function getEndedAt(): \DateTime
    {
        return $this->endedAt;
    }

    #[ORM\PrePersist]
    public function setEndedAt($endedAt): void
    {
        $this->endedAt = new \DateTime();
    }
}