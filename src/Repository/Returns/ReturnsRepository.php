<?php

namespace App\Repository\Returns;

use App\Entity\Returns\Returns;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Returns|null find($id, $lockMode = null, $lockVersion = null)
 * @method Returns|null findOneBy(array $criteria, array $orderBy = null)
 * @method Returns[]    findAll()
 * @method Returns[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReturnsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Returns::class);
    }
}
