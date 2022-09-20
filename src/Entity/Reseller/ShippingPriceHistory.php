<?php

namespace App\Entity\Reseller;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\PriceProfile;
use App\Entity\Reseller\Customer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\ShippingPriceHistoryRepository")
 * @ORM\Table(name="reseller_shipping_price_history", schema="reseller")
 */
class ShippingPriceHistory
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $changeTime;
    
    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $customer;
    
    /**
     * @var PriceProfile
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\PriceProfile")
     * @ORM\JoinColumn(nullable=true)
     */
    private $priceProfile;
    
    /**
     * @var ShippingPriceProfile
     *
     * @ORM\ManyToOne(targetEntity="ShippingPriceProfile")
     * @ORM\JoinColumn(nullable=true)
     */
    private $shippingPriceProfile;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getChangeTime(): \DateTime
    {
        return $this->changeTime;
    }
    
    public function setChangeTime(\DateTime $changeTime): void
    {
        $this->changeTime = $changeTime;
    }
    
    public function getCustomer(): Customer
    {
        return $this->customer;
    }
    
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }
    
    public function getPriceProfile(): ?PriceProfile
    {
        return $this->priceProfile;
    }
    
    public function setPriceProfile(?PriceProfile $priceProfile): void
    {
        $this->priceProfile = $priceProfile;
    }
    
    public function getShippingPriceProfile(): ?ShippingPriceProfile
    {
        return $this->shippingPriceProfile;
    }
    
    public function setShippingPriceProfile(?ShippingPriceProfile $shippingPriceProfile): void
    {
        $this->shippingPriceProfile = $shippingPriceProfile;
    }
    
}
