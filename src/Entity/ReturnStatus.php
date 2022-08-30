<?php

namespace App\Entity;

use App\Repository\ReturnStatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReturnStatusRepository")
 * @ORM\Table(name="return_status")
 */
class ReturnStatus
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @var Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Returns::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @ORM\JoinColumn(nullable=false)
     */
    private $returns;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReturns(): ?Returns
    {
        return $this->returns;
    }

    public function setReturns(?Returns $returns): self
    {
        $this->returns = $returns;

        return $this;
    }
}
