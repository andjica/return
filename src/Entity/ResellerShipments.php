<?php

namespace App\Entity;

use App\Repository\ResellerShipmentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResellerShipmentsRepository")
 * @ORM\Table(name="reseller_shipments")
 */
class ResellerShipments
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
    private $externalId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $senderAddressId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $returnAddressId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $deliveryAddressId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shippingOptionId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $domainId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $customerId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $webshopOrderId;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $labelsNum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $reference;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $removed;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $has_labels;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $is_shipped;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $has_errors;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $shop_type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $route_name;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $product_items_qty;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $distributor_reseller_data_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_shipped_status_date;
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $manualPrice;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $isManualPrice;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isNetwork;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(int $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getSenderAddressId(): ?int
    {
        return $this->senderAddressId;
    }

    public function setSenderAddressId(int $senderAddressId): self
    {
        $this->senderAddressId = $senderAddressId;

        return $this;
    }

    public function getReturnAddressId(): ?int
    {
        return $this->returnAddressId;
    }

    public function setReturnAddressId(int $returnAddressId): self
    {
        $this->returnAddressId = $returnAddressId;

        return $this;
    }

    public function getDeliveryAddressId(): ?int
    {
        return $this->deliveryAddressId;
    }

    public function setDeliveryAddressId(int $deliveryAddressId): self
    {
        $this->deliveryAddressId = $deliveryAddressId;

        return $this;
    }

    public function getShippingOptionId(): ?int
    {
        return $this->shippingOptionId;
    }

    public function setShippingOptionId(?int $shippingOptionId): self
    {
        $this->shippingOptionId = $shippingOptionId;

        return $this;
    }

    public function getDomainId(): ?int
    {
        return $this->domainId;
    }

    public function setDomainId(int $domainId): self
    {
        $this->domainId = $domainId;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
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

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getLabelsNum(): ?int
    {
        return $this->labelsNum;
    }

    public function setLabelsNum(int $labelsNum): self
    {
        $this->labelsNum = $labelsNum;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
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

    public function getRemoved(): ?bool
    {
        return $this->removed;
    }

    public function setRemoved(bool $removed): self
    {
        $this->removed = $removed;

        return $this;
    }

    public function getHasLabels(): ?bool
    {
        return $this->has_labels;
    }

    public function setHasLabels(bool $has_labels): self
    {
        $this->has_labels = $has_labels;

        return $this;
    }

    public function getIsShipped(): ?bool
    {
        return $this->is_shipped;
    }

    public function setIsShipped(bool $is_shipped): self
    {
        $this->is_shipped = $is_shipped;

        return $this;
    }

    public function getHasErrors(): ?bool
    {
        return $this->has_errors;
    }

    public function setHasErrors(bool $has_errors): self
    {
        $this->has_errors = $has_errors;

        return $this;
    }

    public function getShopType(): ?int
    {
        return $this->shop_type;
    }

    public function setShopType(int $shop_type): self
    {
        $this->shop_type = $shop_type;

        return $this;
    }

    public function getRouteName(): ?string
    {
        return $this->route_name;
    }

    public function setRouteName(string $route_name): self
    {
        $this->route_name = $route_name;

        return $this;
    }

    public function getProductItemsQty(): ?int
    {
        return $this->product_items_qty;
    }

    public function setProductItemsQty(int $product_items_qty): self
    {
        $this->product_items_qty = $product_items_qty;

        return $this;
    }

    public function getDistributorResellerDataId(): ?int
    {
        return $this->distributor_reseller_data_id;
    }

    public function setDistributorResellerDataId(?int $distributor_reseller_data_id): self
    {
        $this->distributor_reseller_data_id = $distributor_reseller_data_id;

        return $this;
    }

    public function getLastShippedStatusDate(): ?\DateTimeInterface
    {
        return $this->last_shipped_status_date;
    }

    public function setLastShippedStatusDate(?\DateTimeInterface $last_shipped_status_date): self
    {
        $this->last_shipped_status_date = $last_shipped_status_date;

        return $this;
    }

    public function getManualPrice(): ?float
    {
        return $this->manualPrice;
    }

    public function setManualPrice(float $manualPrice): self
    {
        $this->manualPrice = $manualPrice;

        return $this;
    }

    public function getIsManualPrice(): ?int
    {
        return $this->isManualPrice;
    }

    public function setIsManualPrice(int $isManualPrice): self
    {
        $this->isManualPrice = $isManualPrice;

        return $this;
    }

    public function getIsNetwork(): ?bool
    {
        return $this->isNetwork;
    }

    public function setIsNetwork(bool $isNetwork): self
    {
        $this->isNetwork = $isNetwork;

        return $this;
    }

    public function isRemoved(): ?bool
    {
        return $this->removed;
    }

    public function isHasLabels(): ?bool
    {
        return $this->has_labels;
    }

    public function isIsShipped(): ?bool
    {
        return $this->is_shipped;
    }

    public function isHasErrors(): ?bool
    {
        return $this->has_errors;
    }

    public function isIsNetwork(): ?bool
    {
        return $this->isNetwork;
    }
}
