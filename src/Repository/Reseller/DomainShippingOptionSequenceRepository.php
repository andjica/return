<?php

namespace App\Repository\Reseller;

use App\Entity\Reseller\DomainShippingOptionSequence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DomainShippingOptionSequenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomainShippingOptionSequence::class);
    }
}
