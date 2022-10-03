<?php

namespace App\Entity\Returns;

use App\Entity\Common\Country;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Reseller\ShipmentItem;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Returns\ReturnsRepository")
 * @ORM\Table(name="return_returns")
 */
class Returns
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
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $webshop_order_id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */
    private $user_email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */
    private $client_email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */
    private $client_name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $company_name;

    // /**
    //  * @var int
    //  *
    //  * @ORM\Column(type="integer")
    //  */
    // private $status_id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=155, nullable=true)
     */
    private $action;

    // /**
    //  * @var int
    //  *
    //  * @ORM\Column(type="integer", nullable=true)
    //  */
    // private $return_quantity;

    // /**
    //  * @var string
    //  *
    //  * @ORM\Column(type="string", length=255)
    //  */
    // private $reason;

    //for status table :) to get name
    /**
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\Country", inversedBy="returns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
    */
    private $post_code;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $confirmed;

    // /**
    //  * @ORM\ManyToOne(targetEntity="App\Entity\Reseller\ShipmentItem", cascade={"persist", "remove"})
    //  * @ORM\JoinColumn(nullable=true)
    //  */
    // private $item;

    
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
    private $updated_at;

    
    public function getId(): ?int
    {
        return $this->id;
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

    public function getWebshopOrderId(): ?string
    {
        return $this->webshop_order_id;
    }

    public function setWebshopOrderId(string $webshop_order_id): self
    {
        $this->webshop_order_id = $webshop_order_id;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->user_email;
    }

    public function setUserEmail(string $user_email): self
    {
        $this->user_email = $user_email;

        return $this;
    }

    public function getClientEmail(): ?string
    {
        return $this->client_email;
    }

    public function setClientEmail(string $client_email): self
    {
        $this->client_email = $client_email;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): self
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    // public function getStatusId(): ?int
    // {
    //     return $this->status_id;
    // }

    // public function setStatusId(int $status_id): self
    // {
    //     $this->status_id = $status_id;

    //     return $this;
    // }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

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

    // public function getReturnQuantity(): ?int
    // {
    //     return $this->return_quantity;
    // }

    // public function setReturnQuantity(?int $return_quantity): self
    // {
    //     $this->return_quantity = $return_quantity;

    //     return $this;
    // }

    // public function getReason(): ?string
    // {
    //     return $this->reason;
    // }

    // public function setReason(string $reason): self
    // {
    //     $this->reason = $reason;

    //     return $this;
    // }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }


    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    // public function getItemsId(): ?int
    // {
    //     return $this->items_id;
    // }

    // public function setItemsId(int $items_id): self
    // {
    //     $this->items_id = $items_id;

    //     return $this;
    // }

    public function getPostCode(): ?string
    {
        return $this->post_code;
    }

    public function setPostCode(?string $post_code): self
    {
        $this->post_code = $post_code;

        return $this;
    }

    // public function getItem(): ?ShipmentItem
    // {
    //     return $this->item;
    // }

    // public function setItem(ShipmentItem $item): self
    // {
    //     $this->item = $item;

    //     return $this;
    // }

    public function getConfirmed(): ?int
    {
        return $this->confirmed;
    }

    public function setConfirmed(?int $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }

}
