<?php

namespace App\Entity\Reseller;

use App\Entity\Common\Country;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\ShippingOption;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\ShippingPriceRepository")
 * @ORM\Table(name="reseller_shipping_prices", schema="reseller")
 */
class ShippingPrice
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
     * @var ShippingPriceHistory
     *
     * @ORM\ManyToOne(targetEntity="ShippingPriceHistory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shippingPriceHistory;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @var ShippingOption
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\ShippingOption")
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

    /**
     * @ORM\OneToOne(
     *      targetEntity="ShippingColliPrice",
     *      mappedBy="shippingPrice",
     *      cascade={"remove"}
     * )
     */
    private $colliPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShippingPriceHistory(): ShippingPriceHistory
    {
        return $this->shippingPriceHistory;
    }

    public function setShippingPriceHistory(ShippingPriceHistory $shippingPriceHistory): void
    {
        $this->shippingPriceHistory = $shippingPriceHistory;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    public function getShippingOption(): ?ShippingOption
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

    public function getColliPrice(): ?ShippingColliPrice
    {
        return $this->colliPrice;
    }

}
