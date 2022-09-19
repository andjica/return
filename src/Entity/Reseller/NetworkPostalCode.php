<?php

namespace App\Entity\Reseller;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\PostalCode;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\NetworkPostalCodeRepository")
 * @ORM\Table(name="reseller_network_postal_codes",  schema="reseller")
 */
class NetworkPostalCode
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
     * @var PostalCode
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\PostalCode")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postalCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $timeFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $timeTo;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $pickup;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $delivery;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostalCode(): ?PostalCode
    {
        return $this->postalCode;
    }

    public function setPostalCode(PostalCode $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function setEndNumber(int $endNumber): void
    {
        $this->endNumber = $endNumber;
    }

    public function getTimeFrom(): ?\DateTime
    {
        return $this->timeFrom;
    }

    public function setTimeFrom(\DateTime $timeFrom): void
    {
        $this->timeFrom = $timeFrom;
    }

    public function getTimeTo(): ?\DateTime
    {
        return $this->timeTo;
    }

    public function setTimeTo(\DateTime $timeTo): void
    {
        $this->timeTo = $timeTo;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getPickup(): ?bool
    {
        return $this->pickup;
    }

    public function setPickup(bool $pickup): void
    {
        $this->pickup = $pickup;
    }

    public function getDelivery(): ?bool
    {
        return $this->delivery;
    }

    public function setDelivery(bool $delivery): void
    {
        $this->delivery = $delivery;
    }

}
