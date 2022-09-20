<?php

namespace App\Repository\Reseller;

use App\Entity\Reseller\Domain;
use App\Entity\Reseller\NetworkRoute;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reseller\NetworkPlanningDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class NetworkRouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NetworkRoute::class);
    }

    /*
     * Get the Count.
     */
    public function getCount(string $search_key)
    {

        $qb = $this->createQueryBuilder('nr');

        $qb->select('COUNT(nr.id)');

        if (!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('nr.name', ':key')
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

        $qb = $this->createQueryBuilder('nr')
            ->setFirstResult($page * $perpage)
            ->setMaxResults($perpage);

        if (!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('nr.name', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->execute();

    }

    /*
    * Get count of Customer's all routes per page.
    */
    public function findDomainAllPerPageCount(Domain $domain, string $search_key)
    {

        $qb = $this->createQueryBuilder('nr');

        $qb->select('COUNT(nr.id)');

        $skip_ids = [];

        foreach ($domain->getNetworkRoutes() as $networkRoute) {
            $skip_ids[] = $networkRoute->getId();
        }

        if (!empty($skip_ids)) {
            $qb->andWhere($qb->expr()->notIn('nr.id', ':skip_ids'))->setParameter(':skip_ids', $skip_ids);
        }

        if (!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('nr.name', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->getSingleScalarResult();

    }

    /*
    * Get the list of Customer's all routes per page.
    */
    public function findDomainAllPerPage(Domain $domain, int $page, int $perpage, string $search_key)
    {

        $qb = $this->createQueryBuilder('nr')
            ->setFirstResult($page * $perpage)
            ->setMaxResults($perpage);

        $skip_ids = [];

        foreach ($domain->getNetworkRoutes() as $networkRoute) {
            $skip_ids[] = $networkRoute->getId();
        }

        if (!empty($skip_ids)) {
            $qb->andWhere($qb->expr()->notIn('nr.id', ':skip_ids'))->setParameter(':skip_ids', $skip_ids);
        }

        if (!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('nr.name', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->execute();

    }


    /*
    * Get count of Customer's selected routes per page.
    */
    public function findDomainSelectedPerPageCount(Domain $domain, string $search_key)
    {

        $qb = $this->createQueryBuilder('nr');

        $qb->select('COUNT(nr.id)');

        $skip_ids = [];

        foreach ($domain->getNetworkRoutes() as $networkRoute) {
            $skip_ids[] = $networkRoute->getId();
        }

        if (!empty($skip_ids)) {

            $qb->andWhere($qb->expr()->in('nr.id', ':skip_ids'))->setParameter(':skip_ids', $skip_ids);

            if (!empty($search_key)) {

                $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->like('nr.name', ':key')
                ));

                $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

            }

            return $qb->getQuery()->getSingleScalarResult();

        }

        return 0;

    }

    /*
        * Get the list of Customer's all routes per page.
        */
    public function findDomainSelectedPerPage(Domain $domain, int $page, int $perpage, string $search_key)
    {

        $qb = $this->createQueryBuilder('nr')
            ->setFirstResult($page * $perpage)
            ->setMaxResults($perpage);

        $skip_ids = [];

        foreach ($domain->getNetworkRoutes() as $networkRoute) {
            $skip_ids[] = $networkRoute->getId();
        }

        if (!empty($skip_ids)) {

            $qb->andWhere($qb->expr()->in('nr.id', ':skip_ids'))->setParameter(':skip_ids', $skip_ids);

            if (!empty($search_key)) {

                $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->like('nr.name', ':key')
                ));

                $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

            }

            return $qb->getQuery()->execute();

        }

        return [];

    }

    /*
     * Get route by postal code.
     */
    public function getRouteByPostalCode(string $postalCode): ?NetworkRoute
    {

        $qb = $this->createQueryBuilder('nr')
            ->innerJoin('nr.postalCodes', 'nrpc')
            ->leftJoin('nrpc.postalCode', 'pc')
            ->where('pc.startNumber <= :postal_code')
            ->andWhere('pc.endNumber >= :postal_code')
            ->andWhere('nr.enabled = :enabled')
            ->setParameter(':postal_code', (int)substr($postalCode, 0, 4))
            ->setParameter(':enabled', true);

        $result = $qb->getQuery()->execute();

        return isset($result[0]) ? $result[0] : null;

    }


    /*
     * Get the Count per network planning date.
     */
    public function getCountByNetworkPlanningDate(NetworkPlanningDate $planningDate, string $search_key)
    {

        $exceptIds = [];

        foreach ($planningDate->getDateRoutes() as $dateRoute) {

            if($dateRoute->getNetworkRoute() != null) {
                $exceptIds[] = $dateRoute->getNetworkRoute()->getId();
            }

        }

        $qb = $this->createQueryBuilder('nr');

        $qb->select('COUNT(nr.id)')
            ->where('nr.enabled = :enabled')
            ->setParameter(':enabled', true);

        if (!empty($exceptIds)) {
            $qb->andWhere($qb->expr()->notIn('nr.id', ':ids'))->setParameter(':ids', $exceptIds);
        }

        if (!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('nr.name', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->getSingleScalarResult();

    }

    /*
     * Get per page by network planning date.
     */
    public function findPerPageByNetworkPlanningDate(NetworkPlanningDate $planningDate, int $page, int $perpage, string $search_key)
    {

        $exceptIds = [];

        foreach ($planningDate->getDateRoutes() as $dateRoute) {

            if($dateRoute->getNetworkRoute() != null) {
                $exceptIds[] = $dateRoute->getNetworkRoute()->getId();
            }

        }

        $qb = $this->createQueryBuilder('nr')
            ->where('nr.enabled = :enabled')
            ->setParameter(':enabled', true)
            ->setFirstResult($page * $perpage)
            ->setMaxResults($perpage);

        if (!empty($exceptIds)) {
            $qb->andWhere($qb->expr()->notIn('nr.id', ':ids'))->setParameter(':ids', $exceptIds);
        }

        if (!empty($search_key)) {

            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('nr.name', ':key')
            ));

            $qb->setParameter(':key', '%' . addcslashes($search_key, '%') . '%');

        }

        return $qb->getQuery()->execute();

    }


}
