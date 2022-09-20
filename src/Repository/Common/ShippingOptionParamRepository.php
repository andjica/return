<?php

namespace App\Repository\Common;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Common\ShippingOptionParam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingOptionParamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingOptionParam::class);
    }
}
