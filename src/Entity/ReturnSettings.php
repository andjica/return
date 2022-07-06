<?php

namespace App\Entity;

use App\Repository\ReturnSettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlankValidator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReturnSettingsRepository")
 * @ORM\Table(name="return_settings")
 */
class ReturnSettings
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
     * @ORM\Column(type="string", length=255)
     */
    private $image_logo;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $image_background;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $return_period;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageLogo(): ?string
    {
        return $this->image_logo;
    }

    public function setImageLogo(string $image_logo): self
    {
        $this->image_logo = $image_logo;

        return $this;
    }

    public function getImageBackground(): ?string
    {
        return $this->image_background;
    }

    public function setImageBackground(string $image_background): self
    {
        $this->image_background = $image_background;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReturnPeriod(): ?int
    {
        return $this->return_period;
    }

    public function setReturnPeriod(int $return_period): self
    {
        $this->return_period = $return_period;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
    
}
