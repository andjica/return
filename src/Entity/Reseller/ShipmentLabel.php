<?php

namespace App\Entity\Reseller;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\ShipmentLabelRepository")
 * @ORM\Table(name="reseller_shipment_labels", schema="reseller")
 */
class ShipmentLabel
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
     * @var Shipment
     *
     * @ORM\ManyToOne(targetEntity="Shipment", inversedBy="labels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shipment;


    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $labelNumber;


       /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $labelFileName;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShipment(): Shipment
    {
        return $this->shipment;
    }

    public function setShipment(Shipment $shipment): void
    {
        $this->shipment = $shipment;
    }

    

    public function getLabelNumber(): string
    {
        return $this->labelNumber;
    }

    public function setLabelNumber(string $labelNumber): void
    {
        $this->labelNumber = $labelNumber;
    }


    public function getLabelFileName(): string
    {
        return $this->labelFileName;
    }

    public function setLabelFileName(string $labelFileName): void
    {
        $this->labelFileName = $labelFileName;
    }

}
