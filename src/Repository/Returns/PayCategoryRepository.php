<?php

namespace App\Repository\Returns;

use App\Entity\Returns\PayCategory;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<PayCategory>
 *
 * @method PayCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayCategory[]    findAll()
 * @method PayCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayCategory::class);
    }
}
