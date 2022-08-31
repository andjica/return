<?php

namespace App\Entity\Reseller;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\ShipmentItemRepository")
 * @ORM\Table(name="reseller_shipment_items", schema="reseller")
 */
class ShipmentItem
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    
    /**
     * @var Shipment
     *
     * @ORM\ManyToOne(targetEntity="Shipment", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private Shipment $shipment;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $sku;
    
    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private int $qty;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $title;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $location;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private float $weight;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private float $length;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private float $width;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private float $height;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private float $price;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }
    
    public function setShipment(Shipment $shipment): void
    {
        $this->shipment = $shipment;
    }
    
    public function getSku(): ?string
    {
        return $this->sku;
    }
    
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }
    
    public function getQty(): ?int
    {
        return $this->qty;
    }
    
    public function setQty(int $qty): void
    {
        $this->qty = $qty;
    }
    
        public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    
    public function getLocation(): ?string
    {
        return $this->location;
    }
    
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
    
    public function getWeight(): ?float
    {
        return $this->weight;
    }
    
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }
    
    public function getLength(): ?float
    {
        return $this->length;
    }
    
    public function setLength(float $length): void
    {
        $this->length = $length;
    }
    
    public function getWidth(): ?float
    {
        return $this->width;
    }
    
    public function setWidth(float $width): void
    {
        $this->width = $width;
    }
    
    public function getHeight(): ?float
    {
        return $this->height;
    }
    
    public function setHeight(float $height): void
    {
        $this->height = $height;
    }
    
    public function getPrice(): ?float
    {
        return $this->price;
    }
    
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
