<?php

namespace App\Entity\Returns;

use App\Entity\Common\Country;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Returns\ReturnSettingsRepository")
 * @ORM\Table(name="return_settings")
 */
class ReturnSettings
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
     * @ORM\Column(type="string", length=255)
     */
    private $company_name;

     /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $client_name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

     /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $image_logo;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $image_background;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $return_period;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $house_nummber;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\Country", inversedBy="settings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city_name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
    */
    private $post_code;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageLogo(): ?string
    {
        return $this->image_logo;
    }

    public function setImageLogo(string $image_logo): self
    {
        $this->image_logo = $image_logo;

        return $this;
    }

    public function getImageBackground(): ?string
    {
        return $this->image_background;
    }

    public function setImageBackground(string $image_background): self
    {
        $this->image_background = $image_background;

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

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): self
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReturnPeriod(): ?int
    {
        return $this->return_period;
    }

    public function setReturnPeriod(int $return_period): self
    {
        $this->return_period = $return_period;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
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

    public function getHouseNumber(): ?string
    {
        return $this->house_nummber;
    }
    
    public function setHouseNumber(?string $house_nummber): void
    {
        $this->house_nummber = $house_nummber;
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

    public function getCityName(): ?string
    {
        return $this->city_name;
    }

    public function setCityName(?string $city_name): self
    {
        $this->city_name = $city_name;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->post_code;
    }

    public function setPostCode(?string $post_code): self
    {
        $this->post_code = $post_code;

        return $this;
    }
    
}
