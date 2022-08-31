<?php

namespace App\Repository\Returns;

use App\Entity\Returns\Status;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Status|null find($id, $lockMode = null, $lockVersion = null)
 * @method Status|null findOneBy(array $criteria, array $orderBy = null)
 * @method Status[]    findAll()
 * @method Status[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Status::class);
    }

    public function notInStatus($value)
   {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id != :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
   }
}
