<?php

namespace App\Entity\Returns;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Returns\ReturnShipmentsRepository")
 * @ORM\Table(name="return_return_shipments")
 */
class ReturnShipments
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $return_id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $shipment_id;

     /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $labels_url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private \DateTime $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReturnId(): ?int
    {
        return $this->return_id;
    }

    public function setReturnId(int $return_id): self
    {
        $this->return_id = $return_id;

        return $this;
    }

    public function getShipmentId(): ?int
    {
        return $this->shipment_id;
    }

    public function setShipmentId(int $shipment_id): self
    {
        $this->shipment_id = $shipment_id;

        return $this;
    }

    public function getLabelsUrl(): ?string 
    {
        return $this->labels_url;
    }
    public function setLabelsUrl(string $labels_url): self 
    {
        $this->labels_url = $labels_url;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
    

   
}