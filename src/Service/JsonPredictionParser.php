<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;
use Exception;
use App\Entity\Image;
use App\Entity\Metal;
use App\Entity\Period;
use App\Entity\Method;
use App\Entity\Prediction;
use Doctrine\ORM\EntityManagerInterface;

final class JsonPredictionParser
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function parse(Period $period, Metal $metal, Method $method, string $json): void
    {
        $json = json_decode($json, true);

        if (!is_array($json)) {
            throw new Exception();
        }

        $json = reset($json);
        $image = new Image($json['Image']);
        $now = new DateTime();

        $this->entityManager->persist($image);

        foreach ($json['Data'] as $value) {
            $prediction = (new Prediction($now))
                ->setPeriod($period)
                ->setDate(date_create()->setTimestamp($value['Date'] / 1000))
                ->setImage($image)
                ->setMetal($metal)
                ->setMethod($method)
                ->setValue($value['Predictions']);

            $this->entityManager->persist($prediction);
        }

        $this->entityManager->flush();
    }
}