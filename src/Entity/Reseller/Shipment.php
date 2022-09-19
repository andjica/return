<?php

namespace App\Entity\Reseller;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\ShipmentRepository")
 * @ORM\Table(name="reseller_shipments", schema="reseller")
 */
class Shipment
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $webshopOrderId;

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="Address")
     * @ORM\JoinColumn(nullable=false)
     */
    private Address $deliveryAddress;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;


     /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $reference;
    
     /**
     * @var Domain
     *
     * @ORM\ManyToOne(targetEntity="Domain")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domain;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebshopOrderId(): ?string
    {
        return $this->webshopOrderId;
    }

    public function setWebshopOrderId(?string $webshopOrderId): self
    {
        $this->webshopOrderId = $webshopOrderId;

        return $this;
    }

    public function getDeliveryAddress(): ?Address
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(Address $deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDomain(): Domain
    {
        return $this->domain;
    }

    public function setDomain(Domain $domain): void
    {
        $this->domain = $domain;
    }

}
