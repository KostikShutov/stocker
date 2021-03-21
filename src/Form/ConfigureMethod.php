<?php

declare(strict_types=1);

namespace App\Form;

use DateTimeInterface;
use App\Entity\Metal;
use App\Entity\Method;
use App\Entity\Period;

final class ConfigureMethod
{
    private Metal $metal;

    private Method $method;

    private Period $period;

    private ?DateTimeInterface $start = null;

    private ?DateTimeInterface $end = null;

    private string $provider;

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

    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?DateTimeInterface $end): self
    {
        $this->end = $end;

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
}
