<?php

namespace App\Repository\Common;

use App\Entity\Common\CostPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CostPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CostPrice::class);
    }
    
    /**
     * Get Shipping Options per Country and Distributor.
     */
    public function shippingOptionsPerCountryAndDistributor($country, $distributor)
    {
        
        return $this->createQueryBuilder('cp')
            ->leftJoin('cp.country', 'c')
            ->leftJoin('cp.shippingOption', 'op')
            ->where('cp.country = :country')
            ->andWhere('op.distributor = :distributor')
            ->andWhere('cp.enabled = :enabled')
            ->andWhere('op.enabled = :enabled')
            ->setParameter(':country', $country)
            ->setParameter(':distributor', $distributor)
            ->setParameter(':enabled', true)
            ->getQuery()
            ->execute();
        
    }
    
}
