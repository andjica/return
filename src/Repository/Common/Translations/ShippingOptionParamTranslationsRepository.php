<?php

namespace App\Repository\Common\Translations;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Common\Translation\ShippingOptionParamTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingOptionParamTranslationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingOptionParamTranslation::class);
    }
}
