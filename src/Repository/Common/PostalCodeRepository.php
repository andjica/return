<?php

namespace App\Repository\Common;

use App\Entity\Common\Country;
use App\Entity\Common\PostalCode;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class PostalCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostalCode::class);
    }

    /*
     * Get the Count.
     */
    public function getCount(string $search_key)
    {

        $qb = $this->createQueryBuilder('pc');

        $qb->select('COUNT(pc.id)');

        if (!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('pc.startNumber', ':key'),
                $qb->expr()->like('pc.endNumber', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->getSingleScalarResult();

    }

    /*
    * Get the list of postal codes per page.
    */
    public function findPerPage(int $page, int $perpage, string $search_key)
    {

        $qb = $this->createQueryBuilder('pc')
            ->setFirstResult($page * $perpage)
            ->setMaxResults($perpage);

        if (!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('pc.startNumber', ':key'),
                $qb->expr()->like('pc.endNumber', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->execute();

    }

    /*
   * Get PostalCode when value in between.
   */
    public function getInBetween(int $value, Country $country): ?PostalCode
    {

        $qb = $this->createQueryBuilder('pc')
            ->where('pc.startNumber <= :value')
            ->andWhere('pc.endNumber >= :value')
            ->setParameter(':value', $value)
            ->andWhere('pc.country = :country')
            ->setParameter(':country', $country)
            ->setFirstResult(0)
            ->setMaxResults(1);

        $result = $qb->getQuery()->execute();

        return isset($result[0]) ? $result[0] : null;

    }

}
