<?php

namespace App\Repository\Reseller;

use App\Entity\Reseller\ShippingColliPrice;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingColliPriceRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingColliPrice::class);
    }    
    
    public function deleteByIds(array $ids)
    {
        
        if(!empty($ids)) {
            
            $qb = $this->createQueryBuilder('ccp')
                ->delete()
                ->where('ccp.id IN (:ids)')
                ->setParameter(':ids', $ids);

            $qb->getQuery()->execute();
        
        }
        
    }
    
}
