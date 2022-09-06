<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\ShippingSubOption;
use App\Entity\Common\ShippingOptionParam;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\ShippingOptionRepository")
 * @ORM\Table(name="common_shipping_options", schema="common")
 */
class ShippingOption 
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
    private $key_name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $code;
    
    /**
     * @var Distributor
     *
     * @ORM\ManyToOne(targetEntity="Distributor")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $distributor;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $notes;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $length;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $width;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $height;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $sequenceWeight;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $isColli;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;
    
    /**
     * @var ShippingOptionTranslation[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Common\Translation\ShippingOptionTranslation",
     *      mappedBy="shippingOption"
     * )
     */
    private $translations;
    
    /**
     * @var Translation[]|ArrayCollection
     */
    private $translation;
    
    /**
     * @var ShippingOptionParam[]|ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="ShippingOptionParam", cascade={"persist"})
     * @ORM\JoinTable(name="common_shipping_options_param_per_option", schema="common")
     */
    private $params;
    
    /**
     * @var ShippingOptionParamNumeric[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="ShippingOptionParamNumeric",
     *      mappedBy="shippingOption"
     * )
     */
    private $numericParams;
    
    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->params = new ArrayCollection();
        $this->translation = new ArrayCollection();
        $this->numericParams = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        if(isset($this->translation[0])) {
            return $this->translation[0]->getNameTranslation();
        }
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

    public function getKeyName(): ?string
    {
        return $this->key_name;
    }
    
    public function setKeyName(string $key_name): void
    {
        $this->key_name = $key_name;
    }
    
    public function getDistributor(): ?Distributor
    {
        return $this->distributor;
    }
    
    public function setDistributor(Distributor $distributor): void
    {
        $this->distributor = $distributor;
    }
    
    public function getNotes(): ?string
    {
        if(isset($this->translation[0])) {
            return $this->translation[0]->getNoteTranslation();
        }
        return $this->notes;
    }
    
    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(float $length): void
    {
        $this->length = $length;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    public function getSequenceWeight(): ?int
    {
        return $this->sequenceWeight;
    }

    public function setSequenceWeight(int $sequenceWeight): void
    {
        $this->sequenceWeight = $sequenceWeight;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }
    
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
    
    public function getIsColli(): ?bool
    {
        return $this->isColli;
    }
    
    public function setIsColli(bool $isColli): void
    {
        $this->isColli = $isColli;
    }
    
        public function getTranslation(?Language $language)
    {
        
        if(!isset($this->translation[0])) {
            $this->translation = $this->translations->matching(Criteria::create()->where(Criteria::expr()->eq('language', $language))->andWhere(Criteria::expr()->eq('enabled', true)));
        }
        
        return $this;
        
    }
    
    public function getParams(): Collection
    {
        return $this->params;
    }
    
    public function getParamsExceptHidden(): Collection
    {
        return $this->params->matching(Criteria::create()->where(Criteria::expr()->neq('widgetType', 6)));
    }
    
    public function getParam(int $widgetType): ?ShippingOptionParam
    {
        
        $result = $this->params->matching(Criteria::create()->where(Criteria::expr()->eq('widgetType', $widgetType)))->first();
        
        if($result instanceof ShippingOptionParam) {
            return $result;
        }
        
        return null;
        
    }
    
    public function getNumericParam(ShippingOptionParam $shippingOptionParam): ?ShippingOptionParamNumeric
    {
        
        $result = $this->numericParams->matching(Criteria::create()->where(Criteria::expr()->eq('shippingOptionParam', $shippingOptionParam)))->first();
            
        if($result instanceof ShippingOptionParamNumeric) {
            return $result;
        }
        
        return null;
        
    }
    
    public function getServicePointParam(): ?ShippingOptionParam
    {
        
        $result = $this->params->matching(Criteria::create()->where(Criteria::expr()->eq('widgetType', 2)))->first();
        
        if($result instanceof ShippingOptionParam) {
            return $result;
        }
        
        return null;
        
    }
    
    public function isServicePoint(): bool
    {
        return ($this->getServicePointParam() instanceof ShippingOptionParam);
    }
    
    
    
}
