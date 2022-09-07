<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Common\ShippingOption;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\CostPriceRepository")
 * @ORM\Table(name="common_cost_prices", schema="common")
 */
class CostPrice
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
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;
    
    /**
     * @var Shipping Option
     *
     * @ORM\ManyToOne(targetEntity="ShippingOption")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shippingOption;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float", options={"default" : 0})
     */
    private $price;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getCountry(): Country
    {
        return $this->country;
    }
    
    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }
    
    public function getShippingOption(): ShippingOption
    {
        return $this->shippingOption;
    }
    
    public function setShippingOption(ShippingOption $shippingOption): void
    {
        $this->shippingOption = $shippingOption;
    }
    
    public function getPrice(): ?float
    {
        return $this->price;
    }
    
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
    
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }
    
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
    
}
