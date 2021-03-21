<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MethodRepository")
 * @ORM\Table(name="methods")
 */
class Method implements Information
{
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
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="method")
     */
    private Collection|array $predictions;

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
}
