<?php

namespace App\Repository\Reseller;

use App\Entity\Reseller\ShippingPriceProfile;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingPriceProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingPriceProfile::class);
    }
    
}
