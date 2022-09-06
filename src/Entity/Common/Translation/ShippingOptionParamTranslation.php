<?php

namespace App\Entity\Common\Translation;

use App\Entity\Common\Language;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\ShippingOptionParam;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\Translations\ShippingOptionParamTranslationsRepository")
 * @ORM\Table(name="common_shipping_option_param_translations", schema="common")
 */
class ShippingOptionParamTranslation 
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
     * @var ShippingOptionParam
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\ShippingOptionParam", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shippingOptionParam;
    
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
    private $translationName;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $translationDescription;
    
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
    
    public function getShippingOptionParam(): ?ShippingOptionParam
    {
        return $this->shippingOptionParam;
    }
    
    public function setShippingOptionParam(ShippingOptionParam $shippingOptionParam): void
    {
        $this->shippingOptionParam = $shippingOptionParam;
    }
    
    public function getLanguage(): ?Language
    {
        return $this->language;
    }
    
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }
    
    public function getTranslationName(): ?string
    {
        return $this->translationName;
    }
    
    public function setTranslationName(string $translationName): void
    {
        $this->translationName = $translationName;
    }
    
    public function getTranslationDescription(): ?string
    {
        return $this->translationDescription;
    }
    
    public function setTranslationDescription(string $translationDescription): void
    {
        $this->translationDescription = $translationDescription;
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
