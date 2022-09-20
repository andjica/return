<?php

namespace App\Repository\Reseller;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reseller\DomainDistributorSequence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DomainDistributorSequenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomainDistributorSequence::class);
    }
}
