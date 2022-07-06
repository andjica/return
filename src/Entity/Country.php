<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 * @ORM\Table(name="country")
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
     */
    private $name;

    /**
     *
     * @ORM\OneToMany(
     *      targetEntity="Returns",
     *      mappedBy="countries"
     * )
     */
    private $returns;

    public function __construct()
    {
        $this->returns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Returns>
     */
    public function getReturns(): Collection
    {
        return $this->returns;
    }

    public function addReturn(Returns $return): self
    {
        if (!$this->returns->contains($return)) {
            $this->returns[] = $return;
            $return->setCountries($this);
        }

        return $this;
    }

    public function removeReturn(Returns $return): self
    {
        if ($this->returns->removeElement($return)) {
            // set the owning side to null (unless already changed)
            if ($return->getCountries() === $this) {
                $return->setCountries(null);
            }
        }

        return $this;
    }
}
