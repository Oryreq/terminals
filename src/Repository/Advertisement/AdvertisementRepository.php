<?php

namespace App\Repository\Advertisement;

use App\Entity\Advertisement\Advertisement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class AdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }
}