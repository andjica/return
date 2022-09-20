<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\NlCityRepository")
 * @ORM\Table(name="common_nl_cities", schema="common")
 */
class NlCity
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
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $township;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $townshipCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $provinceCode;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $regionCode;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getTownship(): ?string
    {
        return $this->township;
    }

    public function setTownship(string $township): void
    {
        $this->township = $township;
    }

    public function getTownshipCode(): ?string
    {
        return $this->townshipCode;
    }

    public function setTownshipCode(string $townshipCode): void
    {
        $this->townshipCode = $townshipCode;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): void
    {
        $this->province = $province;
    }

    public function getProvinceCode(): ?string
    {
        return $this->provinceCode;
    }

    public function setProvinceCode(string $provinceCode): void
    {
        $this->provinceCode = $provinceCode;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    public function getRegionCode(): ?string
    {
        return $this->regionCode;
    }

    public function setRegionCode(string $regionCode): void
    {
        $this->regionCode = $regionCode;
    }

}
