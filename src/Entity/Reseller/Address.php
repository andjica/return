<?php

namespace App\Entity\Reseller;

use App\Entity\Common\Country;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\AddressRepository")
 * @ORM\Table(name="reseller_address", schema="reseller")
 */
class Address
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $companyName;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $postalCode;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $houseNumber;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $houseNumberAddition;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $street;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $city;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $phone;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $customerId;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
    
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }
    
    public function setCompanyName(?string $companyName): void
    {
        $this->companyName = $companyName;
    }
    
    public function getCountry(): Country
    {
        return $this->country;
    }
    
    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }
    
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }
    
    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }
    
    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }
    
    public function setHouseNumber(?string $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }
    
    public function getHouseNumberAddition(): ?string
    {
        return $this->houseNumberAddition;
    }
    
    public function setHouseNumberAddition(?string $houseNumberAddition): void
    {
        $this->houseNumberAddition = $houseNumberAddition;
    }
    
    public function getStreet(): ?string
    {
        return $this->street;
    }
    
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }
    
    public function getCity(): ?string
    {
        return $this->city;
    }
    
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }
    
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
    
    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }
    
    public function setCustomerId(?string $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getNLAddress(): string
    {
        return $this->street . ' ' . trim($this->houseNumber . ' ' . $this->houseNumberAddition) . ', ' . $this->postalCode . ', ' . $this->city;
    }
    
}
