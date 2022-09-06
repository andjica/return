<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Common\Translation\CountryTranslation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\CountryRepository")
 * @ORM\Table(name="common_countries", schema="common")
 */
class Country 
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
    private $iso_code;
    
    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(nullable=true)
     */
    private $language;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $weight;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isEu;
    
    /**
     * @var Translations[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Common\Translation\CountryTranslation",
     *      mappedBy="country"
     * )
     */
    private $translations;
    
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
    
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
    
    public function getISOCode(): ?string
    {
        return $this->iso_code;
    }
    
    public function setISOCode(string $iso_code): void
    {
        $this->iso_code = $iso_code;
    }
    
    public function getLanguage(): ?Language
    {
        return $this->language;
    }
    
    public function setLanguage(?Language $language): void
    {
        $this->language = $language;
    }
    
    public function getWeight(): ?float
    {
        return $this->weight;
    }
    
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }
    
    public function getIsEu(): ?bool
    {
        return $this->isEu;
    }
    
    public function setIsEu(bool $isEu): void
    {
        $this->isEu = $isEu;
    }
    
    public function getTranslation(?Language $language): string
    {
        
        $translation = $this->translations->matching(Criteria::create()->where(Criteria::expr()->eq('language', $language))->andWhere(Criteria::expr()->eq('enabled', true)));
        
        if(isset($translation[0])) {
            return $translation[0]->getTranslation();
        }
        
        return $this->getName();
        
    }
    
}
