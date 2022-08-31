<?php

namespace App\Repository\Returns;

use App\Entity\Returns\ReturnSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use  Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReturnSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReturnSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReturnSettings[]    findAll()
 * @method ReturnSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReturnSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReturnSettings::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ReturnSettings $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findLastInserted()
    {
        return $this
            ->createQueryBuilder("r")
            // ->orderBy("id", "DESC")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


}
