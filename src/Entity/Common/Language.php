<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\LanguageRepository")
 * @ORM\Table(name="common_languages", schema="common")
 */
class Language 
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
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $native_name;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $lang_code;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $weight;
    
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
    
    public function getNativeName(): ?string
    {
        return $this->native_name;
    }
    
    public function setNativeName(string $native_name): void
    {
        $this->native_name = $native_name;
    }
    
    public function getLangCode(): ?string
    {
        return $this->lang_code;
    }
    
    public function setLangCode(string $lang_code): void
    {
        $this->lang_code = $lang_code;
    }
    
    public function getWeight(): ?float
    {
        return $this->weight;
    }
    
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }
    
}
