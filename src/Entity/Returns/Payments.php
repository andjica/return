<?php

namespace App\Entity\Returns;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Returns\PaymentsRepository")
 * @ORM\Table(name="return_payments")
 */
class Payments
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $public_key;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $secret_key;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $image;

    /**
     * @ORM\ManyToOne(targetEntity=PayCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private PayCategory $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublickey(): ?string
    {
        return $this->public_key;
    }

    public function setPublickey(string $public_key): self
    {
        $this->public_key = $public_key;

        return $this;
    }

    public function getSecretkey(): ?string
    {
        return $this->secret_key;
    }

    public function setSecretkey(?string $secret_key): self
    {
        $this->secret_key = $secret_key;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPayCategory(): ?PayCategory
    {
        return $this->category;
    }

    public function setPayCategory(?PayCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
