<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\DistributorRepository")
 * @ORM\Table(name="common_distributors", schema="common")
 */
class Distributor 
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
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $key_name;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $providerClass;
    
    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     */
    private $logoDisplayHeight;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $website;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $phone;
    
    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $labelType;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $bolComTransporter;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $fuelChargeEnabled;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    public function getKeyName(): ?string
    {
        return $this->key_name;
    }
    
    public function setKeyName(string $key_name): void
    {
        $this->key_name = $key_name;
    }
    
    public function getProviderClass(): ?string
    {
        return $this->providerClass;
    }
    
    public function setProviderClass(string $providerClass): void
    {
        $this->providerClass = $providerClass;
    }
    
    public function setLogoDisplayHeight(int $logoDisplayHeight): void
    {
        $this->logoDisplayHeight = $logoDisplayHeight;
    }
    
    public function getLogoDisplayHeight(): ?int
    {
        return $this->logoDisplayHeight;
    }
    
    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }
    
    public function getWebsite(): ?string
    {
        return $this->website;
    }
    
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
    
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    public function setLabelType(int $labelType): void
    {
        $this->labelType = $labelType;
    }
    
    public function getLabelType(): ?int
    {
        return $this->labelType;
    }
    
    public function setBolComTransporter(string $bolComTransporter): void
    {
        $this->bolComTransporter = $bolComTransporter;
    }
    
    public function getBolComTransporter(): ?string
    {
        return $this->bolComTransporter;
    }

    public function setFuelChargeEnabled(bool $fuelChargeEnabled): void
    {
        $this->fuelChargeEnabled = $fuelChargeEnabled;
    }

    public function getFuelChargeEnabled(): ?bool
    {
        return $this->fuelChargeEnabled;
    }
    
}
