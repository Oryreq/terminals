<?php

namespace App\Repository\StandbyMode;

use App\Entity\StandbyMode\StandbyMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class StandbyModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StandbyMode::class);
    }
}