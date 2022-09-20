<?php

namespace App\Repository\Common;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Common\ShippingOptionParamNumeric;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingOptionParamNumericRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingOptionParamNumeric::class);
    }
}
