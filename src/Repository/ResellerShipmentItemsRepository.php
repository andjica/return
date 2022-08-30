<?php

namespace App\Repository;

use App\Entity\ResellerShipmentItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResellerShipmentItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResellerShipmentItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResellerShipmentItems[]    findAll()
 * @method ResellerShipmentItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResellerShipmentItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResellerShipmentItems::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ResellerShipmentItems $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ResellerShipmentItems $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ResellerShipmentItems[] Returns an array of ResellerShipmentItems objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResellerShipmentItems
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
