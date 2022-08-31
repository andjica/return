<?php

namespace App\Repository\Returns;

use App\Entity\Returns\ReturnVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReturnVideos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReturnVideos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReturnVideos[]    findAll()
 * @method ReturnVideos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReturnVideosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReturnVideos::class);
    }

}
