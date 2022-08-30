<?php

namespace App\Repository;

use App\Entity\ReasonSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReasonSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReasonSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReasonSettings[]    findAll()
 * @method ReasonSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReasonSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReasonSettings::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ReasonSettings $entity, bool $flush = true): void
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
    public function remove(ReasonSettings $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ReasonSettings[] Returns an array of ReasonSettings objects
    //  */
    
    // public function findByOthers($value)
    // {
    //     return $this->createQueryBuilder('r')
    //         ->andWhere('r.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('r.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?ReasonSettings
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //example for fandind except some row
    public function findothers($val)
    {
        return $this
            ->createQueryBuilder("r")
            ->where('r.id != :val')
            ->setParameter('val', $val)
            ->getQuery()
            ->getResult();
    }
    
}
