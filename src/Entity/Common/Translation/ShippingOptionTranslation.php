<?php

namespace App\Entity\Common\Translation;

use App\Entity\Common\Language;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\ShippingOption;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\Translations\ShippingOptionTranslationsRepository")
 * @ORM\Table(name="common_shipping_option_translations", schema="common")
 */
class ShippingOptionTranslation 
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
     * @var ShippingOprion
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\ShippingOption", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shippingOption;
    
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
    private $nameTranslation;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $noteTranslation;
    
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
    
    public function getShippingOption(): ?ShippingOption
    {
        return $this->shippingOption;
    }
    
    public function setShippingOption(ShippingOption $shippingOption): void
    {
        $this->shippingOption = $shippingOption;
    }
    
    public function getLanguage(): ?Language
    {
        return $this->language;
    }
    
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }
    
    public function getNameTranslation(): ?string
    {
        return $this->nameTranslation;
    }
    
    public function setNameTranslation(string $nameTranslation): void
    {
        $this->nameTranslation = $nameTranslation;
    }    
    
    public function getNoteTranslation(): ?string
    {
        return $this->noteTranslation;
    }
    
    public function setNoteTranslation(string $noteTranslation): void
    {
        $this->noteTranslation = $noteTranslation;
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
