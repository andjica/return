<?php

namespace App\Entity\Returns;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Returns\ShippingOptionSettingsRepository")
 * @ORM\Table(name="return_shipping_option_settings")
 */class ShippingOptionSettings
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

    private $shipping_option_id;

     /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */

    private $shipping_option_name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */

    private $shipping_option_key_name;
      /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $country_id;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;

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

    public function getShippingOptionId(): ?int
    {
        return $this->shipping_option_id;
    }

    public function setShippingOptionId(int $shipping_option_id): self
    {
        $this->shipping_option_id = $shipping_option_id;

        return $this;
    }

    public function setShippingOptionName(string $shipping_option_name): self
    {
        $this->shipping_option_name = $shipping_option_name;

        return $this;
    }

    public function getShippingOptionName(): ?string
    {
        return $this->shipping_option_name;
    }

    public function setShippingOptionKeyName(string $shipping_option_key_name): self
    {
        $this->shipping_option_key_name = $shipping_option_key_name;

        return $this;
    }

    public function getShippingOptionKeyName(): ?string
    {
        return $this->shipping_option_key_name;
    }

    public function getCountryId(): ?int
    {
        return $this->country_id;
    }

    public function setCountryId(int $country_id): self
    {
        $this->country_id = $country_id;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
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
