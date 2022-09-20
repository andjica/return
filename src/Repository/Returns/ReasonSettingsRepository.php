<?php

namespace App\Repository\Returns;

use App\Entity\Returns\ReasonSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
