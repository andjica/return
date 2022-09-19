<?php

namespace App\Repository\Reseller;

use App\Entity\Reseller\NetworkRoute;
use App\Entity\Reseller\NetworkPostalCode;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class NetworkPostalCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NetworkPostalCode::class);
    }

    /*
     * Get the Count.
     */
    public function getCount(string $search_key)
    {

        $qb = $this->createQueryBuilder('npc');

        $qb->select('COUNT(npc.id)')->leftJoin('npc.postalCode', 'pc')
            ->where('npc.enabled = :true')->setParameter(':true', true);

        if(!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('pc.startNumber', ':key'),
                $qb->expr()->like('pc.endNumber', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->getSingleScalarResult();

    }

    /*
    * Get per page.
    */
    public function findPerPage(int $page, int $perpage, string $search_key)
    {

        $qb = $this->createQueryBuilder('npc')
            ->leftJoin('npc.postalCode', 'pc')
            ->setFirstResult($page * $perpage)
            ->setMaxResults($perpage)
            ->where('npc.enabled = :true')->setParameter(':true', true);

        if(!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('pc.startNumber', ':key'),
                $qb->expr()->like('pc.endNumber', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->execute();

    }

    /*
    * Get per page except selected.
    */
    public function findPerPageExeptSelected(NetworkRoute $networkRoute, int $page, int $perpage, string $search_key)
    {

        $qb = $this->createQueryBuilder('npc')
            ->leftJoin('npc.postalCode', 'pc')
            ->setFirstResult($page * $perpage)
            ->setMaxResults($perpage)
            ->where('npc.enabled = :true')->setParameter(':true', true);

        $skip_ids = [];

        foreach($networkRoute->getPostalCodes() as $networkPostalCode) {
            $skip_ids[] = $networkPostalCode->getId();
        }

        if(!empty($skip_ids)) {
            $qb->andWhere($qb->expr()->notIn('npc.id', ':skip_ids'))->setParameter(':skip_ids', $skip_ids);
        }

        if(!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('pc.startNumber', ':key'),
                $qb->expr()->like('pc.endNumber', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->execute();

    }

    /*
    * Get per page selected only.
    */
    public function findPerPageSelected(NetworkRoute $networkRoute, int $page, int $perpage, string $search_key)
    {

        $qb = $this->createQueryBuilder('npc')
            ->leftJoin('npc.postalCode', 'pc')
            ->setFirstResult($page * $perpage)
            ->setMaxResults($perpage)
            ->where('npc.enabled = :true')->setParameter(':true', true);

        $skip_ids = [];

        foreach($networkRoute->getPostalCodes() as $networkPostalCode) {
            $skip_ids[] = $networkPostalCode->getId();
        }

        if(!empty($skip_ids)) {

            $qb->andWhere($qb->expr()->in('npc.id', ':skip_ids'))->setParameter(':skip_ids', $skip_ids);

            if(!empty($search_key)) {

                $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->like('pc.startNumber', ':key'),
                    $qb->expr()->like('pc.endNumber', ':key')
                ));

                $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

            }

            return $qb->getQuery()->execute();

        }

        return [];

    }

}
