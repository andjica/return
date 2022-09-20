<?php

namespace App\Repository\Reseller;

use App\Entity\Reseller\Customer;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reseller\ShippingPriceHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingPriceHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingPriceHistory::class);
    }


    public function getByDate(Customer $customer)
    {
        $qb = $this->createQueryBuilder('ph')
            ->where('ph.customer = :customer')
            ->setParameter(':customer', $customer)
            ->andWhere('ph.changeTime < :change_time')
            ->setParameter(':change_time', new \DateTime('2021-12-30'))
            ->orderBy('ph.id', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->execute();

    }
}
