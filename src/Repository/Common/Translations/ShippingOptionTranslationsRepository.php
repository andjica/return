<?php

namespace App\Repository\Common\Translations;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Common\Translation\ShippingOptionTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingOptionTranslationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingOptionTranslation::class);
    }
}
