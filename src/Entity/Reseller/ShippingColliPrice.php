<?php

namespace App\Entity\Reseller;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\ShippingColliPriceRepository")
 * @ORM\Table(name="reseller_shipping_colli_prices", schema="reseller")
 */
class ShippingColliPrice
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
     * @var Shipping Price History
     *
     * @ORM\OneToOne(targetEntity="ShippingPrice", inversedBy="colliPrice")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shippingPrice;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float", options={"default" : 0})
     */
    private $price2;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float", options={"default" : 0})
     */
    private $price3;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float", options={"default" : 0})
     */
    private $price4;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float", options={"default" : 0})
     */
    private $price5;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getShippingPrice(): ?ShippingPrice
    {
        return $this->shippingPrice;
    }
    
    public function setShippingPrice(ShippingPrice $shippingPrice): void
    {
        $this->shippingPrice = $shippingPrice;
    }
    
    public function getPrice2(): ?float
    {
        return $this->price2;
    }
    
    public function setPrice2(float $price2): void
    {
        $this->price2 = $price2;
    }
    
    public function getPrice3(): ?float
    {
        return $this->price3;
    }
    
    public function setPrice3(float $price3): void
    {
        $this->price3 = $price3;
    }
    
    public function getPrice4(): ?float
    {
        return $this->price4;
    }
    
    public function setPrice4(float $price4): void
    {
        $this->price4 = $price4;
    }
    
    public function getPrice5(): ?float
    {
        return $this->price5;
    }
    
    public function setPrice5(float $price5): void
    {
        $this->price5 = $price5;
    }
    
}
