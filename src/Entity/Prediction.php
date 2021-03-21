<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PredictionRepository")
 * @ORM\Table(name="predictions")
 */
class Prediction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Metal", inversedBy="predictions")
     */
    private Metal $metal;

    /**
     * @ORM\ManyToOne(targetEntity="Method", inversedBy="methods")
     */
    private Method $method;

    /**
     * @ORM\ManyToOne(targetEntity="Period", inversedBy="periods")
     */
    private Period $period;

    /**
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="predictions")
     */
    private ?Image $image = null;

    /**
     * @ORM\Column(type="date")
     */
    private DateTimeInterface $date;

    /**
     * @ORM\Column(type="float")
     */
    private float $value;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private DateTimeInterface $createdAt;

    public function __construct(DateTimeInterface $createdAt = null)
    {
        $this->createdAt = is_null($createdAt) ? new DateTime() : $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMetal(): Metal
    {
        return $this->metal;
    }

    public function setMetal(Metal $metal): self
    {
        $this->metal = $metal;

        return $this;
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function setMethod(Method $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getPeriod(): Period
    {
        return $this->period;
    }

    public function setPeriod(Period $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
