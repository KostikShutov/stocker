<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
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

    public function getMethod(): string
    {
        return $this->options['method'];
    }

    public function getMetal(): string
    {
        return $this->options['metal'];
    }

    public function getPeriod(): string
    {
        return $this->options['period'];
    }
}
