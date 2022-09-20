<?php

namespace App\Entity\Reseller;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Reseller\ShippingPriceHistory;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\CustomerRepository")
 * @ORM\Table(name="reseller_customers", schema="reseller")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isReseller;

    /**
     * @var ShippingPriceHistory
     *
     * @ORM\ManyToOne(targetEntity="ShippingPriceHistory")
     * @ORM\JoinColumn(nullable=true)
     */
    private $currentShippingPriceHistory;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsReseller(): ?bool
    {
        return $this->isReseller;
    }

    public function setIsReseller(bool $isReseller): void
    {
        $this->isReseller = $isReseller;
    }

    public function getCurrentShippingPriceHistory(): ?ShippingPriceHistory
    {
        return $this->currentShippingPriceHistory;
    }

    public function setCurrentShippingPriceHistory(?ShippingPriceHistory $currentShippingPriceHistory): void
    {
        $this->currentShippingPriceHistory = $currentShippingPriceHistory;
    }

}