<?php

namespace App\Repository\Common;

use App\Entity\Common\ShippingOption;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingOption::class);
    }

    public function getLatest(): ?ShippingOption
    {

        $qb = $this->createQueryBuilder('so')
            ->orderBy('so.id', 'DESC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getResult();

        return $result[0] ?? null;

    }

}
