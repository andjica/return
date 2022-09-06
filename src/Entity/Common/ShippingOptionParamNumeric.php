<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\ShippingSubOption;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\ShippingOptionParamNumericRepository")
 * @ORM\Table(name="common_shipping_option_param_numeric", schema="common")
 */
class ShippingOptionParamNumeric
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
     * @var ShippingOption
     *
     * @ORM\ManyToOne(targetEntity="ShippingOption", inversedBy="numericParams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shippingOption;
    
    /**
     * @var ShippingOption
     *
     * @ORM\ManyToOne(targetEntity="ShippingOptionParam")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shippingOptionParam;
    
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $paramValue;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getShippingOption(): ?ShippingOption
    {
        return $this->shippingOption;
    }
    
    public function setShippingOption(ShippingOption $shippingOption): void
    {
        $this->shippingOption = $shippingOption;
    }
    
    public function getShippingOptionParam(): ?ShippingOptionParam
    {
        return $this->shippingOptionParam;
    }
    
    public function setShippingOptionParam(ShippingOptionParam $shippingOptionParam): void
    {
        $this->shippingOptionParam = $shippingOptionParam;
    }
    
    public function getParamValue(): ?int
    {
        return $this->paramValue;
    }
    
    public function setParamValue(int $paramValue): void
    {
        $this->paramValue = $paramValue;
    }
    
}
