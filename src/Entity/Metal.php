<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MetalRepository")
 * @ORM\Table(name="metals")
 */
class Metal implements Information
{
    const METAL_SILVER = 'silver';
    const METAL_GOLD = 'gold';
    const METAL_PALLADIUM = 'palladium';
    const METAL_PLATINUM = 'platinum';

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
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="metal")
     */
    private Collection|array $predictions;

    /**
     * @var Collection|Stock[]
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="metal")
     */
    private Collection|array $stocks;

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

    /**
     * @return Collection|Stock[]
     */
    public function getStocks(): Collection|array
    {
        return $this->stocks;
    }

    /**
     * @param Collection|Stock[] $stocks
     */
    public function setStocks(Collection|array $stocks): self
    {
        $this->stocks = $stocks;

        return $this;
    }
}
