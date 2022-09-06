<?php

namespace App\Entity\Common;

use App\Service\Helper;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Common\ShippingSubOption;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\ShippingOptionParamRepository")
 * @ORM\Table(name="common_shipping_option_params", schema="common")
 */
class ShippingOptionParam
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
    private $systemName;
    
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
     */
    private $description;
    
    /**
     * @var smallint
     *
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     */
    private $widgetType;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $required;
    
    /**
     * @var Translations[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="App\Entity\Common\Translation\ShippingOptionParamTranslation",
     *      mappedBy="shippingOptionParam"
     * )
     */
    private $translations;
    
    /**
     * @var Translation[]|ArrayCollection
     */
    private $translation;
    
    private $widgetTypes;
    
    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->translation = new ArrayCollection();
        $this->widgetTypes = [];
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getSystemName(): ?string
    {
        return $this->systemName; 
    }
    
    public function setSystemName(string $systemName): void
    {
        $this->systemName = $systemName; 
    }
    
    public function getName(): ?string
    {
        
        if(isset($this->translation[0])) {
            return $this->translation[0]->getTranslationName();
        }
        
        return $this->name;
        
    }
    
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    public function getDescription(): ?string
    {
        
        if(isset($this->translation[0])) {
            return $this->translation[0]->getTranslationDescription();
        }
        
        return $this->description;
        
    }
    
    public function getWidgetType(): ?int
    {
        return $this->widgetType;
    }
    
    public function setWidgetType(int $widgetType): void
    {
        $this->widgetType = $widgetType;
    }
    
    public function getRequired(): ?bool
    {
        return $this->required;
    }
    
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }
    
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    
    public function getTranslation(?Language $language)
    {
        
        if(!isset($this->translation[0])) {
            $this->translation = $this->translations->matching(Criteria::create()->where(Criteria::expr()->eq('language', $language))->andWhere(Criteria::expr()->eq('enabled', true)));
        }
        
        return $this;
        
    }
    
    public function setWidgetTypes($widgetTypes): void
    {
        $this->widgetTypes = $widgetTypes;
    }
    
    static function getWidgetTypes($type = null)
    {
        
        $types = [
            0 => 'Simple text field',
            1 => 'Price field',
            2 => 'Service point',
            3 => 'Checkbox',
            4 => 'Text area',
            5 => 'Price field with value multiple of the given',
            6 => 'Hidden value',
            7 => 'SendCloud insurance',
            8 => 'Date',
            9 => 'Time',
            10 => 'Float field',
            11 => 'Express charge',
        ];
        
        return is_numeric($type) ? $types[$type] : $types;
        
    }
    
    public function paramName(): string
    {
        return $this->name . ' (' . $this->getWidgetTypes($this->widgetType) . ')';
    }
    
}
