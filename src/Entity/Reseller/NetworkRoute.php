<?php

namespace App\Entity\Reseller;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\NetworkRouteRepository")
 * @ORM\Table(name="reseller_network_routes")
 */
class NetworkRoute
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
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @var NetworkPostalCode[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="NetworkPostalCode")
     * @ORM\JoinTable(name="network_route_postal_codes")
     */
    private $postalCodes;

    public function __construct()
    {
        $this->postalCodes = new ArrayCollection();
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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getPostalCodes(): Collection
    {
        return $this->postalCodes;
    }

    public function addNetworkPostalCode(NetworkPostalCode $networkPostalCode): void
    {
        if (!$this->postalCodes->contains($networkPostalCode)) {
            $this->postalCodes->add($networkPostalCode);
        }
    }

    public function removeNetworkPostalCode(NetworkPostalCode $networkPostalCode): void
    {
        if ($this->postalCodes->contains($networkPostalCode)) {
            $this->postalCodes->removeElement($networkPostalCode);
        }
    }

    public function getByNetworkPostalCode(NetworkPostalCode $networkPostalCode): ?self
    {
        return $this->postalCodes->get($this->postalCodes->indexOf($networkPostalCode));
    }

}
