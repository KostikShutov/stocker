<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Process;
use App\Entity\Prediction;
use Doctrine\ORM\EntityManagerInterface;

final class JsonPredictionParser
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function parse(Process $process, array $json): void
    {
        $process->setImage($json['Image']);

        foreach ($json['Data'] as $value) {
            $prediction = (new Prediction())
                ->setProcess($process)
                ->setDate(date_create()->setTimestamp($value['Date'] / 1000))
                ->setValue($value['Predictions']);

            $this->entityManager->persist($prediction);
        }

        $this->entityManager->flush();
    }
}
