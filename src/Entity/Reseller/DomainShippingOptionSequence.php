<?php

namespace App\Entity\Reseller;

use App\Entity\Common\Country;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\ShippingOption;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\DomainShippingOptionSequenceRepository")
 * @ORM\Table(name="reseller_domain_shipping_option_sequence", schema="reseller")
 */
class DomainShippingOptionSequence
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
     * @var Domain
     *
     * @ORM\ManyToOne(targetEntity="Domain")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domain;
    
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
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $weight;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getDomain(): Domain
    {
        return $this->domain;
    }
    
    public function setDomain(Domain $domain): void
    {
        $this->domain = $domain;
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
    
    public function getWeight(): ?int
    {
        return $this->weight;
    }
    
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }
    
}
