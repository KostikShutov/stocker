<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PeriodRepository")
 * @ORM\Table(name="periods")
 */
class Period implements Information
{
    const PERIOD_ULTRA_SHORT = 'ultra_short';
    const PERIOD_SHORT = 'short';
    const PERIOD_MIDDLE = 'middle';
    const PERIOD_LONG = 'long';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(unique=true)
     */
    private string $slug;

    /**
     * @ORM\Column
     */
    private string $title;

    /**
     * @var Collection|Prediction[]
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="period")
     */
    private Collection|array $predictions;

    /**
     * @ORM\Column(type="integer")
     */
    private int $days;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPredictions(): Collection|array
    {
        return $this->predictions;
    }

    public function setPredictions(Collection|array $predictions): self
    {
        $this->predictions = $predictions;

        return $this;
    }

    public function getDays(): int
    {
        return $this->days;
    }

    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }
}
