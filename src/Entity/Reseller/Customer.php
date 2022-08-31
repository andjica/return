<?php

namespace App\Entity\Reseller;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reseller\CustomerRepository")
 * @ORM\Table(name="reseller_customers", schema="reseller")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

}