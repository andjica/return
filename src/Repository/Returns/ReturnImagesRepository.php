<?php

namespace App\Repository\Returns;

use App\Entity\Returns\ReturnImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReturnImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReturnImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReturnImages[]    findAll()
 * @method ReturnImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReturnImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReturnImages::class);
    }
}
