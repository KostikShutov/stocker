<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\Table(name="images")
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="text")
     */
    private string $data;

    /**
     * @var Collection|Prediction[]
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="image")
     */
    private Collection|array $predictions;

    /**
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return Collection|Prediction[]
     */
    public function getPredictions(): Collection|array
    {
        return $this->predictions;
    }

    /**
     * @param Collection|Prediction[]| $predictions
     */
    public function setPredictions(Collection|array $predictions): self
    {
        $this->predictions = $predictions;

        return $this;
    }
}
