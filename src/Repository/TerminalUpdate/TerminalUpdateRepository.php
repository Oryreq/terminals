<?php

namespace App\Repository\TerminalUpdate;

use App\Entity\TerminalUpdate\TerminalUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class TerminalUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TerminalUpdate::class);
    }
}