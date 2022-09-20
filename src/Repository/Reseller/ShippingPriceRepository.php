<?php

namespace App\Repository\Reseller;

use App\Entity\Common\Country;
use App\Entity\Reseller\Customer;
use App\Entity\Reseller\Domain;
use App\Entity\Common\Distributor;
use App\Entity\Common\ShippingOption;
use App\Entity\Reseller\ShippingPrice;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Reseller\ShippingPriceHistory;
use App\Entity\Reseller\DomainAPIShippingOptionMatrix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ShippingPriceRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingPrice::class);
    }

    /**
     * Get Distributors.
     */
    public function distributors(ShippingPriceHistory $price_history, ?Country $country = null, string $key_name = ''): array
    {

        $distributors = [];

        $qb = $this->createQueryBuilder('cp')->distinct();

        $qb->leftJoin(
            'App\Entity\Common\CostPrice',
            'cpr',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'cpr.shippingOption = cp.shippingOption'
        );

        $qb->leftJoin('cp.shippingOption', 'op')
            ->leftJoin('op.distributor', 'd')
            ->where('cp.shippingPriceHistory = :price_history')
            ->andWhere('cp.enabled = :enabled')
            ->andWhere('op.enabled = :enabled')
            ->setParameter(':price_history', $price_history)
            ->setParameter(':enabled', true);

        if ($country instanceof Country) {

            $qb->leftJoin('cp.country', 'c')
                ->andWhere('cp.country = :country')
                ->andWhere('cpr.country = :country')
                ->setParameter(':country', $country);

        }

        //->andWhere('d.providerClass NOT IN (:skip_providers)')
        //->setParameter(':skip_providers', ['postnl_post'])

        if (!empty($key_name)) {
            $qb->andWhere('d.key_name = :key_name')->setParameter(':key_name', $key_name);
        }

        $shipping_prices = $qb->getQuery()->execute();

        foreach ($shipping_prices as $shipping_price) {

            $distributor = $shipping_price->getShippingOption()->getDistributor();

            if (!isset($distributors[$distributor->getId()])) {
                $distributors[$distributor->getId()] = $distributor;
            }

        }

        return array_values($distributors);

    }

    /**
     * Get enabled Shipping Options.
     */
    public function shippingOptions(ShippingPriceHistory $price_history, Country $country, ?Distributor $distributor, bool $enabled = true, string $sort = 'weight_asc')
    {

        $qb = $this->createQueryBuilder('cp')
            ->leftJoin('cp.country', 'c')
            ->leftJoin('cp.shippingOption', 'op');

        $qb->leftJoin(
            'App\Entity\Common\CostPrice',
            'cpr',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'cpr.shippingOption = cp.shippingOption'
        );

        $qb->where('cp.country = :country')
            ->andWhere('cpr.country = :country')
            ->andWhere('cp.shippingPriceHistory = :price_history')
            ->andWhere('op.enabled = :enabled')
            ->andWhere('cpr.enabled = :enabled')
            ->setParameter(':country', $country)
            ->setParameter(':price_history', $price_history)
            ->setParameter(':enabled', true);

        if ($sort == 'weight_asc') {
            $qb->orderBy('op.sequenceWeight', 'ASC');
        }

        if ($sort == 'id_asc') {
            $qb->orderBy('op.id', 'ASC');
        }

        if ($distributor instanceof Distributor) {
            $qb->andWhere('op.distributor = :distributor')->setParameter(':distributor', $distributor);
        }

        if ($enabled) {
            $qb->andWhere('cp.enabled = :enabled');
        }

        return $qb->getQuery()->execute();

    }

    /**
     * Get Colli Shipping Options.
     */
    public function colliShippingOptions(ShippingPriceHistory $price_history)
    {

        $qb = $this->createQueryBuilder('cp')
            ->distinct()
            ->leftJoin('cp.shippingOption', 'op');

        $qb->leftJoin(
            'App\Entity\Common\CostPrice',
            'cpr',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'cpr.shippingOption = cp.shippingOption'
        );

        $qb->where('cp.shippingPriceHistory = :price_history')
            ->andWhere('op.isColli = :enabled')
            ->andWhere('op.enabled = :enabled')
            ->andWhere('cpr.enabled = :enabled')
            ->setParameter(':price_history', $price_history)
            ->setParameter(':enabled', true);

        return $qb->getQuery()->execute();

    }

    /**
     * Get particular enabled Shipping Option.
     */
    public function shippingOptionPrice(ShippingPriceHistory $price_history, Country $country, ShippingOption $shipping_option)
    {

        $qb = $this->createQueryBuilder('cp')
            ->leftJoin('cp.country', 'c')
            ->leftJoin('cp.shippingOption', 'op');

        $qb->leftJoin(
            'App\Entity\Common\CostPrice',
            'cpr',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'cpr.shippingOption = cp.shippingOption'
        );

        $qb->where('cp.country = :country')
            ->andWhere('cpr.country = :country')
            ->andWhere('cp.shippingPriceHistory = :price_history')
            ->andWhere('cp.shippingOption = :shipping_option')
            ->andWhere('cp.enabled = :enabled')
            ->andWhere('op.enabled = :enabled')
            ->andWhere('cpr.enabled = :enabled')
            ->setParameter(':country', $country)
            ->setParameter(':shipping_option', $shipping_option)
            ->setParameter(':price_history', $price_history)
            ->setParameter(':enabled', true);

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (\Throwable $e) {
            return null;
        }

    }

    /**
     * Get enabled Shipping Options for API.
     */
    public function apiShippingOptions(ShippingPriceHistory $price_history, Country $country, ?Distributor $distributor, Domain $domain, DomainAPIShippingOptionMatrix $matrix, bool $enabled_only = false)
    {

        $qb = $this->createQueryBuilder('cp')
            ->leftJoin('cp.country', 'c')
            ->leftJoin('cp.shippingOption', 'op');

        $qb->leftJoin(
            'App\Entity\Common\CostPrice',
            'cpr',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'cpr.shippingOption = cp.shippingOption'
        );

        $qb->leftJoin(
            'App\Entity\Reseller\DomainAPIShippingOption',
            'apiso',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'apiso.shippingOption = cp.shippingOption AND apiso.domain = :domain AND apiso.matrix = :matrix'
        );

        $qb->where('cp.country = :country')
            ->andWhere('cpr.country = :country')
            ->andWhere('cp.shippingPriceHistory = :price_history')
            ->andWhere('cp.enabled = :enabled')
            ->andWhere('op.enabled = :enabled')
            ->andWhere('cpr.enabled = :enabled')
            ->setParameter(':country', $country)
            ->setParameter(':price_history', $price_history)
            ->setParameter(':enabled', true)
            ->setParameter(':domain', $domain)
            ->setParameter(':matrix', $matrix)
            ->addOrderBy('apiso.sequenceWeight', 'ASC')
            ->addOrderBy('op.id', 'ASC');

        if ($distributor instanceof Distributor) {
            $qb->andWhere('op.distributor = :distributor')->setParameter(':distributor', $distributor);
        }

        if ($enabled_only) {
            $qb->andWhere('apiso.enabled = :enabled');
        }

        return $qb->getQuery()->execute();

    }

    /**
     * Get enabled all shipping options.
     */
    public function shippingOptionsAll(ShippingPriceHistory $price_history)
    {

        return $this->createQueryBuilder('cp')
            ->leftJoin('cp.shippingOption', 'op')
            ->andWhere('cp.shippingPriceHistory = :price_history')
            ->andWhere('cp.enabled = :enabled')
            ->andWhere('op.enabled = :enabled')
            ->setParameter(':price_history', $price_history)
            ->setParameter(':enabled', true)
            ->getQuery()
            ->execute();

    }

    /**
     * Get enabled countries.
     */
    public function countries(ShippingPriceHistory $price_history)
    {

        return $this->createQueryBuilder('cp')
            ->leftJoin('cp.country', 'c')
            ->leftJoin('cp.shippingOption', 'op')
            ->andWhere('cp.shippingPriceHistory = :price_history')
            ->andWhere('cp.enabled = :enabled')
            ->andWhere('op.enabled = :enabled')
            ->setParameter(':price_history', $price_history)
            ->setParameter(':enabled', true)
            ->addOrderBy('c.isEu', 'DESC')
            ->addOrderBy('c.weight', 'ASC')
            ->getQuery()
            ->execute();

    }

    public function shippingOptionsOrdered(ShippingPriceHistory $price_history, Country $country, Domain $domain)
    {

        return $this->createQueryBuilder('cp')->leftJoin('cp.country', 'c')
            ->leftJoin('cp.shippingOption', 'op')
            ->leftJoin('op.distributor', 'd')
            ->leftJoin(
                'App\Entity\Reseller\DomainDistributorSequence',
                'dds',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'dds.distributor = d.id AND dds.country = :country AND dds.domain = :domain'
            )
            ->leftJoin(
                'App\Entity\Reseller\DomainShippingOptionSequence',
                'dso',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'dso.shippingOption = op.id AND dso.country = :country AND dso.domain = :domain'
            )
            ->where('cp.country = :country')
            ->andWhere('cp.shippingPriceHistory = :price_history')
            ->andWhere('cp.enabled = :price_enabled')
            ->andWhere('op.enabled = :option_enabled')
            ->addOrderBy('dds.weight', 'ASC')
            ->addOrderBy('dso.weight', 'ASC')
            ->addOrderBy('d.name', 'ASC')
            ->addOrderBy('op.name', 'ASC')
            ->setParameter(':country', $country)
            ->setParameter(':price_history', $price_history)
            ->setParameter(':option_enabled', true)
            ->setParameter(':price_enabled', true)
            ->setParameter(':domain', $domain)
            ->getQuery()
            ->execute();

    }

    public function shippingOptionsOrderedAndWeightCheck(ShippingPriceHistory $price_history, Country $country, Domain $domain, float $weight)
    {

        $qb = $this->createQueryBuilder('cp');

        return $qb
            ->leftJoin('cp.country', 'c')
            ->leftJoin('cp.shippingOption', 'op')
            ->leftJoin('op.distributor', 'd')
            ->leftJoin(
                'App\Entity\Reseller\DomainDistributorSequence',
                'dds',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'dds.distributor = d.id AND dds.country = :country AND dds.domain = :domain'
            )
            ->leftJoin(
                'App\Entity\Reseller\DomainShippingOptionSequence',
                'dso',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'dso.shippingOption = op.id AND dso.country = :country AND dso.domain = :domain'
            )
            ->where('cp.country = :country')
            ->andWhere('cp.shippingPriceHistory = :price_history')
            ->andWhere('cp.enabled = :price_enabled')
            ->andWhere('op.enabled = :option_enabled')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('op.weight', 0),
                $qb->expr()->andX(
                    $qb->expr()->gt('op.weight', 0),
                    $qb->expr()->gte('op.weight', $weight)
                )))
            ->addOrderBy('dds.weight', 'ASC')
            ->addOrderBy('dso.weight', 'ASC')
            ->addOrderBy('d.name', 'ASC')
            ->addOrderBy('op.sequenceWeight', 'ASC')
            ->addOrderBy('op.name', 'ASC')
            ->setParameter(':country', $country)
            ->setParameter(':price_history', $price_history)
            ->setParameter(':option_enabled', true)
            ->setParameter(':price_enabled', true)
            ->setParameter(':domain', $domain)
            ->getQuery()
            ->execute();

    }

    public function deleteByHistory(ShippingPriceHistory $price_history)
    {

        $qb = $this->createQueryBuilder('cp')
            ->delete()
            ->where('cp.shippingPriceHistory = :history')
            ->setParameter(':history', $price_history);

        $qb->getQuery()->execute();

    }

    public function deleteByIds(array $ids)
    {

        if (!empty($ids)) {

            $qb = $this->createQueryBuilder('cp')
                ->delete()
                ->where('cp.id IN (:ids)')
                ->setParameter(':ids', $ids);

            $qb->getQuery()->execute();

        }

    }

    public function getCountByCountryAndDistributor(Customer $customer, Country $country, Distributor $distributor): int
    {

        $qb = $this->createQueryBuilder('sp')
            ->select('COUNT(sp.id)')
            ->leftJoin('sp.shippingOption', 'so')
            ->leftJoin('sp.shippingPriceHistory', 'sph')
            ->where('so.distributor = :distributor')
            ->setParameter(':distributor', $distributor)
            ->andWhere('sp.country = :country')
            ->setParameter(':country', $country)
            ->andWhere('sph.customer = :customer')
            ->setParameter(':customer', $customer)
            ->andWhere('sp.enabled = :enabled')
            ->setParameter(':enabled', true)
        ;

        return $qb->getQuery()->getSingleScalarResult();

    }

    public function getNotByPriceHistory(Customer $customer)
    {

        return $this->createQueryBuilder('sp')
            ->leftJoin('sp.shippingPriceHistory', 'sph')
            ->where('sph.customer = :customer')
            ->andWhere('sp.shippingPriceHistory != :price_history')
            ->setParameter(':customer', $customer)
            ->setParameter(':price_history', $customer->getCurrentShippingPriceHistory())
            ->getQuery()
            ->execute();

    }

}
