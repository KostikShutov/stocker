<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProcessRepository")
 * @ORM\Table(name="processes")
 */
class Process
{
    /**
     * Статусы процессов прогнозирования в очереди
     */
    const STATUS_WAITING = 'waiting';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DONE = 'done';

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
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $image = null;

    /**
     * @param Collection|Prediction[]
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="process")
     */
    private Collection|array $predictions;

    /**
     * @ORM\Column
     */
    private string $status;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", name="ended_at", nullable=true)
     */
    private ?DateTimeInterface $endedAt = null;

    /**
     * @ORM\Column(type="json")
     */
    private array $options;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $success = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Prediction[]
     */
    public function getPredictions(): Collection|array
    {
        return $this->predictions;
    }

    /**
     * @param Collection|Prediction[] $predictions
     */
    public function setPredictions(Collection|array $predictions): self
    {
        $this->predictions = $predictions;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isStatusWaiting(): bool
    {
        return self::STATUS_WAITING === $this->status;
    }

    public function isStatusProcessing(): bool
    {
        return self::STATUS_PROCESSING === $this->status;
    }

    public function isStatusDone(): bool
    {
        return self::STATUS_DONE === $this->status;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEndedAt(): ?DateTimeInterface
    {
        return $this->endedAt;
    }

    public function setEndedAt(?DateTimeInterface $endedAt): Process
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function isSuccess(): ?bool
    {
        return $this->success;
    }

    public function setSuccess(?bool $success): self
    {
        $this->success = $success;

        return $this;
    }
}
