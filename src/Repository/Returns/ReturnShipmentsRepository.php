<?php

namespace App\Repository\Returns;

use App\Entity\Returns\ReturnShipments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReturnShipments>
 *
 * @method ReturnShipments|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReturnShipments|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReturnShipments[]    findAll()
 * @method ReturnShipments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReturnShipmentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReturnShipments::class);
    }

    public function add(ReturnShipments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReturnShipments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return ReturnShipments[] Returns an array of ReturnShipments objects
    */
   public function findByExampleField($value): array
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

   public function findOneBySomeField($value): ?ReturnShipments
   {
       return $this->createQueryBuilder('r')
           ->andWhere('r.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
