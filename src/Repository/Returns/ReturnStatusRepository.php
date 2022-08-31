<?php

namespace App\Repository\Returns;

use App\Entity\Returns\ReturnStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReturnStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReturnStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReturnStatus[]    findAll()
 * @method ReturnStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReturnStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReturnStatus::class);
    }
}
