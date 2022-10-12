<?php

namespace App\Entity\Returns;


use Doctrine\ORM\Mapping as ORM;
use App\Entity\Reseller\ShipmentItem;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Returns\ReturnItemsRepository")
 * @ORM\Table(name="return_return_items")
 */
class ReturnItems
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
     * @var Returns
     *
     * @ORM\ManyToOne(targetEntity=Returns::class, inversedBy="returnItems")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\JoinColumn(nullable=false)
     */
    private $return_id;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity=ShipmentItem::class, inversedBy="returnItems")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\JoinColumn(nullable=false)
     */
    private $item_id;

     /**
     * @var int
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $return_item_name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $return_quantity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $reason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $created_at;

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

    public function getReturns(): ?Returns
    {
        return $this->return_id;
    }

    public function setReturns(?Returns $return_id): self
    {
        $this->return_id = $return_id;

        return $this;
    }

    public function getItem(): ?ShipmentItem
    {
        return $this->item_id;
    }

    public function setItem(?ShipmentItem  $item_id): self
    {
        $this->item_id = $item_id;

        return $this;
    }
    public function getItemName(): ?string
    {
        return $this->return_item_name;
    }

    public function setItemName(string $return_item_name): self
    {
        $this->return_item_name = $return_item_name;

        return $this;
    }

    public function getReturnQuantity(): ?int
    {
        return $this->return_quantity;
    }

    public function setReturnQuantity(?int $return_quantity): self
    {
        $this->return_quantity = $return_quantity;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

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