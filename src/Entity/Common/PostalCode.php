<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\PostalCodeRepository")
 * @ORM\Table(name="common_postal_codes", schema="common", indexes={@ORM\Index(name="start_number", columns={"start_number"}), @ORM\Index(name="end_number", columns={"end_number"})})
 */
class PostalCode
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
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $startNumber;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $endNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }
    
    public function getStartNumber(): ?int
    {
        return $this->startNumber;
    }

    public function setStartNumber(int $startNumber): void
    {
        $this->startNumber = $startNumber;
    }

    public function getEndNumber(): ?int
    {
        return $this->endNumber;
    }

    public function setEndNumber(int $endNumber): void
    {
        $this->endNumber = $endNumber;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

}
