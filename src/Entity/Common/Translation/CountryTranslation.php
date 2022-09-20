<?php

namespace App\Entity\Common\Translation;

use App\Entity\Common\Country;
use App\Entity\Common\Language;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\Translations\CountryTranslationsRepository")
 * @ORM\Table(name="common_country_translations", schema="common")
 */
class CountryTranslation 
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $translationId;
    
    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\Country", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;
    
    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\Language")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $language;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $translation;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;
    
    public function getTranslationId(): ?int
    {
        return $this->translationId;
    }
    
    public function getCountry(): ?Country
    {
        return $this->country;
    }
    
    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }
    
    public function getLanguage(): ?Language
    {
        return $this->language;
    }
    
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }
    
    public function getTranslation(): ?string
    {
        return $this->translation;
    }
    
    public function setTranslation(string $translation): void
    {
        $this->translation = $translation;
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
