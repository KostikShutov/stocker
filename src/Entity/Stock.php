<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StockRepository")
 * @ORM\Table(
 *     name="stocks",
 *     uniqueConstraints={
 *         @UniqueConstraint(
 *             name="known_quote_unique",
 *             columns={"metal_id", "open_price", "high_price", "low_price", "close_price", "date", "provider"}
 *         )
 *     }
 * )
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Metal", inversedBy="stocks", cascade={"persist"})
     */
    private Metal $metal;

    /**
     * @ORM\Column(type="float", name="open_price")
     */
    private float $openPrice;

    /**
     * @ORM\Column(type="float", name="high_price")
     */
    private float $highPrice;

    /**
     * @ORM\Column(type="float", name="low_price")
     */
    private float $lowPrice;

    /**
     * @ORM\Column(type="float", name="close_price")
     */
    private float $closePrice;

    /**
     * @ORM\Column(type="date")
     */
    private DateTimeInterface $date;

    /**
     * @ORM\Column
     */
    private string $provider;

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

    public function getOpenPrice(): float
    {
        return $this->openPrice;
    }

    public function setOpenPrice(float $openPrice): self
    {
        $this->openPrice = $openPrice;

        return $this;
    }

    public function getHighPrice(): float
    {
        return $this->highPrice;
    }

    public function setHighPrice(float $highPrice): self
    {
        $this->highPrice = $highPrice;

        return $this;
    }

    public function getLowPrice(): float
    {
        return $this->lowPrice;
    }

    public function setLowPrice(float $lowPrice): self
    {
        $this->lowPrice = $lowPrice;

        return $this;
    }

    public function getClosePrice(): float
    {
        return $this->closePrice;
    }

    public function setClosePrice(float $closePrice): self
    {
        $this->closePrice = $closePrice;

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

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function isEmpty(): bool
    {
        return 0.0 === $this->openPrice
            || 0.0 === $this->highPrice
            || 0.0 === $this->lowPrice
            || 0.0 === $this->closePrice;
    }
}
