<?php

namespace App\Repository\Returns;

use App\Entity\Returns\ShippingOptionSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShippingOptionSettings>
 *
 * @method ShippingOptionSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShippingOptionSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShippingOptionSettings[]    findAll()
 * @method ShippingOptionSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShippingOptionSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingOptionSettings::class);
    }

    public function add(ShippingOptionSettings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShippingOptionSettings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ShippingOptionSettings[] Returns an array of ShippingOptionSettings objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShippingOptionSettings
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
