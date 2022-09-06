<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\PriceProfileRepository")
 * @ORM\Table(name="common_price_profiles", schema="common")
 */
class PriceProfile 
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
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     * @var Smallint
     *
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     */
    private $profileType;
    
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
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getProfileType(): ?int
    {
        return $this->profileType;
    }
    
    public function setProfileType(int $profileType): void
    {
        $this->profileType = $profileType;
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
