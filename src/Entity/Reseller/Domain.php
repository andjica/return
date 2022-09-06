<?php

namespace App\Entity\Reseller;

use App\Entity\Common\Country;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Reseller\Address;
use App\Entity\Reseller\Customer;
use App\Entity\Common\ShippingOption;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\DomainRepository")
 * @ORM\Table(name="reseller_domains", indexes={@ORM\Index(name="api_public_key", columns={"api_public_key"}), @ORM\Index(name="external_id", columns={"external_id"})})
 */
class Domain
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $externalId;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="domains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $senderAddressSameAsAccount;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $returnAddressSameAsAccount;
    
    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="Address")
     * @ORM\JoinColumn(nullable=false)
     */
    private $senderAddress;
    
    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="Address")
     * @ORM\JoinColumn(nullable=false)
     */
    private $returnAddress;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $imageTmpName;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $imageName;
    
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default" : "0"}, nullable=true)
     */
    private $imageWidth;
    
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default" : "0"}, nullable=true)
     */
    private $imageHeight;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $ownSmtp;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $smtpFromEmail;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $smtpHost;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $smtpPort;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $smtpEncryption;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $smtpUsername;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $smtpPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" : ""}, nullable=true)
     */
    private $smtpFromName;
    
    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\Country")
     * @ORM\JoinColumn(nullable=true)
     */
    private $defaultCountry;   
    
    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default" : "0"})
     */
    private $defaultLabelPrinterType;
    
    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default" : "0"})
     */
    private $defaultLabelPrintSequence;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $deliveryAddressPhoneRequired;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $deliveryEmailRequired;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $sendEmailWhenLabelCreated;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $createShipmentPage;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $dpdSaturdayDelivery;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dpdSaturdayDeliveryTimeFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dpdSaturdayDeliveryTimeTo;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $directLabelPrinting;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $showPostNlPostLogo;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $manualShipmentPrice;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $createShipmentSimplePageCompanyName;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $createShipmentSimplePageContactName;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $createShipmentSimplePageContactEmail;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $createShipmentSimplePageContactPhone;
    
    /**
     * @var ShippingOption
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Common\ShippingOption")
     * @ORM\JoinColumn(nullable=true)
     */
    private $createShipmentPageSimpleShippingOption;
    
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $createShipmentSimplePageWeight;
    
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $createShipmentSimplePageLabelsNum;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $apiPublicKey;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $apiSecretKey;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $apiEnabled;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $apiAllowCreateShipments;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $bcc1;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $bcc2;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $bcc3;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;
    
    /**
     * @var array
     */
    private $createShipmentPageSimpleShippingOptionParams = [];

    /**
     * @var NetworkRoute[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="NetworkRoute")
     * @ORM\JoinTable(name="reseller_domain_network_routes")
     */
    private $networkRoutes;

    public function __construct()
    {
        $this->networkRoutes = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(int $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }
    
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(string $name): void
    {
        $this->name = $name;
    }   
    
    public function getSenderAddressSameAsAccount(): ?bool
    {
        return $this->senderAddressSameAsAccount;
    }
    
    public function setSenderAddressSameAsAccount(bool $senderAddressSameAsAccount): void
    {
        $this->senderAddressSameAsAccount = $senderAddressSameAsAccount;
    }
    
    public function getReturnAddressSameAsAccount(): ?bool
    {
        return $this->returnAddressSameAsAccount;
    }
    
    public function setReturnAddressSameAsAccount(bool $returnAddressSameAsAccount): void
    {
        $this->returnAddressSameAsAccount = $returnAddressSameAsAccount;
    }
    
    public function getSenderAddress(): ?Address
    {
        return $this->senderAddress;
    }
    
    public function setSenderAddress(Address $senderAddress): void
    {
        $this->senderAddress = $senderAddress;
    }
    
    public function getReturnAddress(): ?Address
    {
        return $this->returnAddress;
    }
    
    public function setReturnAddress(Address $returnAddress): void
    {
        $this->returnAddress = $returnAddress;
    }
    
    public function getSenderCompanyName(): ?string
    {
        return $this->senderAddress->getCompanyName();
    }
    
    public function setSenderCompanyName(string $senderCompanyName): void
    {
        $this->senderAddress->setCompanyName($senderCompanyName);
    }
    
    public function getSenderName(): ?string
    {
        return $this->senderAddress->getName();
    }
    
    public function setSenderName(string $senderName): void
    {
        $this->senderAddress->setName($senderName);
    }
    
    public function getSenderPostalCode(): ?string
    {
        return $this->senderAddress->getPostalCode();
    }
    
    public function setSenderPostalCode(string $senderPostalCode): void
    {
        $this->senderAddress->setPostalCode($senderPostalCode);
    }
    
    public function getSenderHouseNumber(): ?string
    {
        return $this->senderAddress->getHouseNumber();
    }
    
    public function setSenderHouseNumber(string $senderHouseNumber): void
    {
        $this->senderAddress->setHouseNumber($senderHouseNumber);
    }
    
    public function getSenderHouseNumberAddition(): ?string
    {
        return $this->senderAddress->getHouseNumberAddition();
    }
    
    public function setSenderHouseNumberAddition(string $senderHouseNumberAddition): void
    {
        $this->senderAddress->setHouseNumberAddition($senderHouseNumberAddition);
    }
    
    public function getSenderStreet(): ?string
    {
        return $this->senderAddress->getStreet();
    }
    
    public function setSenderStreet(string $senderStreet): void
    {
        $this->senderAddress->setStreet($senderStreet);
    }
    
    public function getSenderCity(): ?string
    {
        return $this->senderAddress->getCity();
    }
    
    public function setSenderCity(string $senderCity): void
    {
        $this->senderAddress->setCity($senderCity);
    }
    
    public function getSenderPhone(): ?string
    {
        return $this->senderAddress->getPhone();
    }
    
    public function setSenderPhone(string $senderPhone): void
    {
        $this->senderAddress->setPhone($senderPhone);
    }
    
    public function getSenderEmail(): ?string
    {
        return $this->senderAddress->getEmail();
    }
    
    public function setSenderEmail(string $senderEmail): void
    {
        $this->senderAddress->setEmail($senderEmail);
    }
    
    public function getSenderCountry(): ?Country
    {
        return $this->senderAddress->getCountry();
    }
    
    public function setSenderCountry(Country $senderCountry): void
    {
        $this->senderAddress->setCountry($senderCountry);
    }
    
    public function getReturnCompanyName(): ?string
    {
        return $this->returnAddress->getCompanyName();
    }
    
    public function setReturnCompanyName(string $returnCompanyName): void
    {
        $this->returnAddress->setCompanyName($returnCompanyName);
    }
    
    public function getReturnName(): ?string
    {
        return $this->returnAddress->getName();
    }
    
    public function setReturnName(string $returnName): void
    {
        $this->returnAddress->setName($returnName);
    }
    
    public function getReturnPostalCode(): ?string
    {
        return $this->returnAddress->getPostalCode();
    }
    
    public function setReturnPostalCode(string $returnPostalCode): void
    {
        $this->returnAddress->setPostalCode($returnPostalCode);
    }
    
    public function getReturnHouseNumber(): ?string
    {
        return $this->returnAddress->getHouseNumber();
    }
    
    public function setReturnHouseNumber(string $returnHouseNumber): void
    {
        $this->returnAddress->setHouseNumber($returnHouseNumber);
    }
    
    public function getReturnHouseNumberAddition(): ?string
    {
        return $this->returnAddress->getHouseNumberAddition();
    }
    
    public function setReturnHouseNumberAddition(string $returnHouseNumberAddition): void
    {
        $this->returnAddress->setHouseNumberAddition($returnHouseNumberAddition);
    }
    
    public function getReturnStreet(): ?string
    {
        return $this->returnAddress->getStreet();
    }
    
    public function setReturnStreet(string $returnStreet): void
    {
        $this->returnAddress->setStreet($returnStreet);
    }
    
    public function getReturnCity(): ?string
    {
        return $this->returnAddress->getCity();
    }
    
    public function setReturnCity(string $returnCity): void
    {
        $this->returnAddress->setCity($returnCity);
    }
    
    public function getReturnPhone(): ?string
    {
        return $this->returnAddress->getPhone();
    }
    
    public function setReturnPhone(string $returnPhone): void
    {
        $this->returnAddress->setPhone($returnPhone);
    }
    
    public function getReturnEmail(): ?string
    {
        return $this->returnAddress->getEmail();
    }
    
    public function setReturnEmail(string $returnEmail): void
    {
        $this->returnAddress->setEmail($returnEmail);
    }
    
    public function getReturnCountry(): ?Country
    {
        return $this->returnAddress->getCountry();
    }
    
    public function setReturnCountry(Country $returnCountry): void
    {
        $this->returnAddress->setCountry($returnCountry);
    }
    
    public function getImageTmpName(): ?string
    {
        return $this->imageTmpName;
    }
    
    public function setImageTmpName(string $imageTmpName): void
    {
        $this->imageTmpName = $imageTmpName;
    }
    
    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    
    public function setImageName(string $imageName): void
    {
        $this->imageName = $imageName;
    }
    
    public function getImageWidth(): ?int
    {
        return $this->imageWidth;
    }
    
    public function setImageWidth(int $imageWidth): void
    {
        $this->imageWidth = $imageWidth;
    }
    
    public function getImageHeight(): ?int
    {
        return $this->imageHeight;
    }
    
    public function setImageHeight(int $imageHeight): void
    {
        $this->imageHeight = $imageHeight;
    }
    
    public function getOwnSmtp(): ?bool
    {
        return $this->ownSmtp;
    }
    
    public function setOwnSmtp(bool $ownSmtp): void
    {
        $this->ownSmtp = $ownSmtp;
    }
    
    public function getSmtpFromEmail(): ?string
    {
        return $this->smtpFromEmail;
    }
    
    public function setSmtpFromEmail(string $smtpFromEmail): void
    {
        $this->smtpFromEmail = $smtpFromEmail;
    }
    
    public function getSmtpHost(): ?string
    {
        return $this->smtpHost;
    }
    
    public function setSmtpHost(string $smtpHost): void
    {
        $this->smtpHost = $smtpHost;
    }
    
    public function getSmtpPort(): ?string
    {
        return $this->smtpPort;
    }
    
    public function setSmtpPort(string $smtpPort): void
    {
        $this->smtpPort = $smtpPort;
    }
    
    public function getSmtpEncryption(): ?string
    {
        return $this->smtpEncryption;
    }
    
    public function setSmtpEncryption(?string $smtpEncryption): void
    {
        $this->smtpEncryption = $smtpEncryption;
    }
    
    public function getSmtpUsername(): ?string
    {
        return $this->smtpUsername;
    }
    
    public function setSmtpUsername(string $smtpUsername): void
    {
        $this->smtpUsername = $smtpUsername;
    }
    
    public function getSmtpPassword(): ?string
    {
        return $this->smtpPassword;
    }
    
    public function setSmtpPassword(string $smtpPassword): void
    {
        $this->smtpPassword = $smtpPassword;
    }

    public function getSmtpFromName(): ?string
    {
        return $this->smtpFromName;
    }

    public function setSmtpFromName(string $smtpFromName): void
    {
        $this->smtpFromName = $smtpFromName;
    }
    
    public function getDefaultCountry(): ?Country
    {
        return $this->defaultCountry;
    }
    
    public function setDefaultCountry(?Country $defaultCountry): void
    {
        $this->defaultCountry = $defaultCountry;
    }
    
    public function getDefaultLabelPrinterType(): ?int
    {
        return $this->defaultLabelPrinterType;
    }
    
    public function setDefaultLabelPrinterType(int $defaultLabelPrinterType): void
    {
        $this->defaultLabelPrinterType = $defaultLabelPrinterType;
    }
    
    public function getDefaultLabelPrintSequence(): ?int
    {
        return $this->defaultLabelPrintSequence;
    }
    
    public function setDefaultLabelPrintSequence(int $defaultLabelPrintSequence): void
    {
        $this->defaultLabelPrintSequence = $defaultLabelPrintSequence;
    }
    
    public function getDeliveryAddressPhoneRequired(): ?bool
    {
        return $this->deliveryAddressPhoneRequired;
    }
    
    public function setDeliveryAddressPhoneRequired(bool $deliveryAddressPhoneRequired): void
    {
        $this->deliveryAddressPhoneRequired = $deliveryAddressPhoneRequired;
    }

    public function getDeliveryEmailRequired(): ?bool
    {
        return $this->deliveryEmailRequired;
    }

    public function setDeliveryEmailRequired(bool $deliveryEmailRequired): void
    {
        $this->deliveryEmailRequired = $deliveryEmailRequired;
    }

    public function getSendEmailWhenLabelCreated(): ?bool
    {
        return $this->sendEmailWhenLabelCreated;
    }
    
    public function setSendEmailWhenLabelCreated(bool $sendEmailWhenLabelCreated): void
    {
        $this->sendEmailWhenLabelCreated = $sendEmailWhenLabelCreated;
    }
    
    public function getCreateShipmentPage(): ?bool
    {
        return $this->createShipmentPage;
    }
    
    public function setCreateShipmentPage(bool $createShipmentPage): void
    {
        $this->createShipmentPage = $createShipmentPage;
    }

    public function getDpdSaturdayDelivery(): ?bool
    {
        return $this->dpdSaturdayDelivery;
    }

    public function setDpdSaturdayDelivery(bool $dpdSaturdayDelivery): void
    {
        $this->dpdSaturdayDelivery = $dpdSaturdayDelivery;
    }

    public function getDpdSaturdayDeliveryTimeFrom(): ?\DateTime
    {
        return $this->dpdSaturdayDeliveryTimeFrom;
    }

    public function setDpdSaturdayDeliveryTimeFrom(?\DateTime $dpdSaturdayDeliveryTimeFrom): void
    {
        $this->dpdSaturdayDeliveryTimeFrom = $dpdSaturdayDeliveryTimeFrom;
    }

    public function getDpdSaturdayDeliveryTimeTo(): ?\DateTime
    {
        return $this->dpdSaturdayDeliveryTimeTo;
    }

    public function setDpdSaturdayDeliveryTimeTo(?\DateTime $dpdSaturdayDeliveryTimeTo): void
    {
        $this->dpdSaturdayDeliveryTimeTo = $dpdSaturdayDeliveryTimeTo;
    }

    public function getDirectLabelPrinting(): ?bool
    {
        return $this->directLabelPrinting;
    }

    public function setDirectLabelPrinting(bool $directLabelPrinting): void
    {
        $this->directLabelPrinting = $directLabelPrinting;
    }

    public function getShowPostNlPostLogo(): ?bool
    {
        return $this->showPostNlPostLogo;
    }

    public function setShowPostNlPostLogo(bool $showPostNlPostLogo): void
    {
        $this->showPostNlPostLogo = $showPostNlPostLogo;
    }

    public function getManualShipmentPrice(): ?bool
    {
        return $this->manualShipmentPrice;
    }

    public function setManualShipmentPrice(bool $manualShipmentPrice): void
    {
        $this->manualShipmentPrice = $manualShipmentPrice;
    }

    public function getCreateShipmentSimplePageCompanyName(): ?string
    {
        return $this->createShipmentSimplePageCompanyName;
    }
    
    public function setCreateShipmentSimplePageCompanyName(string $createShipmentSimplePageCompanyName): void
    {
        $this->createShipmentSimplePageCompanyName = $createShipmentSimplePageCompanyName;
    }
    
    public function getCreateShipmentSimplePageContactName(): ?string
    {
        return $this->createShipmentSimplePageContactName;
    }
    
    public function setCreateShipmentSimplePageContactName(string $createShipmentSimplePageContactName): void
    {
        $this->createShipmentSimplePageContactName = $createShipmentSimplePageContactName;
    }
    
    public function getCreateShipmentSimplePageContactEmail(): ?string
    {
        return $this->createShipmentSimplePageContactEmail;
    }
    
    public function setCreateShipmentSimplePageContactEmail(string $createShipmentSimplePageContactEmail): void
    {
        $this->createShipmentSimplePageContactEmail = $createShipmentSimplePageContactEmail;
    }
    
    public function getCreateShipmentSimplePageContactPhone(): ?string
    {
        return $this->createShipmentSimplePageContactPhone;
    }
    
    public function setCreateShipmentSimplePageContactPhone(string $createShipmentSimplePageContactPhone): void
    {
        $this->createShipmentSimplePageContactPhone = $createShipmentSimplePageContactPhone;
    }
    
    public function getCreateShipmentPageSimpleShippingOption(): ?ShippingOption
    {
        return $this->createShipmentPageSimpleShippingOption;
    }
    
    public function setCreateShipmentPageSimpleShippingOption(?ShippingOption $createShipmentPageSimpleShippingOption): void
    {
        $this->createShipmentPageSimpleShippingOption = $createShipmentPageSimpleShippingOption;
    }
    
    public function getCreateShipmentSimplePageWeight(): ?float
    {
        return $this->createShipmentSimplePageWeight;
    }
    
    public function setCreateShipmentSimplePageWeight(float $createShipmentSimplePageWeight): void
    {
        $this->createShipmentSimplePageWeight = $createShipmentSimplePageWeight;
    }
    
    public function getCreateShipmentSimplePageLabelsNum(): ?int
    {
        return $this->createShipmentSimplePageLabelsNum;
    }
    
    public function setCreateShipmentSimplePageLabelsNum(int $createShipmentSimplePageLabelsNum): void
    {
        $this->createShipmentSimplePageLabelsNum = $createShipmentSimplePageLabelsNum;
    }
    
    public function setApiPublicKey(string $apiPublicKey): void
    {
        $this->apiPublicKey = $apiPublicKey;
    }
    
    public function getApiPublicKey(): ?string
    {
        return $this->apiPublicKey;
    }
    
    public function setApiSecretKey(string $apiSecretKey): void
    {
        $this->apiSecretKey = $apiSecretKey;
    }
    
    public function getApiSecretKey(): ?string
    {
        return $this->apiSecretKey;
    }
    
    public function setApiEnabled(bool $apiEnabled): void
    {
        $this->apiEnabled = $apiEnabled;
    }
    
    public function getApiEnabled(): ?bool
    {
        return $this->apiEnabled;
    }
    
    public function setApiAllowCreateShipments(bool $apiAllowCreateShipments): void
    {
        $this->apiAllowCreateShipments = $apiAllowCreateShipments;
    }
    
    public function getApiAllowCreateShipments(): ?bool
    {
        return $this->apiAllowCreateShipments;
    }
    
    public function getBcc1(): ?string
    {
        return $this->bcc1;
    }
    
    public function setBcc1(string $bcc1): void
    {
        $this->bcc1 = $bcc1;
    }
    
    public function getBcc2(): ?string
    {
        return $this->bcc2;
    }
    
    public function setBcc2(string $bcc2): void
    {
        $this->bcc2 = $bcc2;
    }
    
    public function getBcc3(): ?string
    {
        return $this->bcc3;
    }
    
    public function setBcc3(string $bcc3): void
    {
        $this->bcc3 = $bcc3;
    }
    
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
    
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }
    
    public function getCreateShipmentPageSimpleShippingOptionParams(): array
    {
        return $this->createShipmentPageSimpleShippingOptionParams;
    }
    
    public function setCreateShipmentPageSimpleShippingOptionParams(array $createShipmentPageSimpleShippingOptionParams): void
    {
        $this->createShipmentPageSimpleShippingOptionParams = $createShipmentPageSimpleShippingOptionParams;
    }

    public function getNetworkRoutes(): Collection
    {
        return $this->networkRoutes;
    }

    public function addNetworkRoute(NetworkRoute $networkRoute): void
    {
        if (!$this->networkRoutes->contains($networkRoute)) {
            $this->networkRoutes->add($networkRoute);
        }
    }

    public function removeNetworkRoute(NetworkRoute $networkRoute): void
    {
        if ($this->networkRoutes->contains($networkRoute)) {
            $this->networkRoutes->removeElement($networkRoute);
        }
    }
    
}