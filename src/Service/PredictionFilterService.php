<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Process;
use App\Entity\Prediction;
use App\Repository\ProcessRepository;
use App\Repository\PredictionRepository;

final class PredictionFilterService
{
    private ProcessRepository $processRepository;

    private PredictionRepository $predictionRepository;

    public function __construct(
        ProcessRepository $processRepository,
        PredictionRepository $predictionRepository
    ) {
        $this->processRepository = $processRepository;
        $this->predictionRepository = $predictionRepository;
    }

    /**
     * @return string[]
     */
    public function getFilters(): array
    {
        $i = 1;
        $filters = [];
        /** @var Process[] $processes */
        $processes = $this->processRepository->findBy(['success' => true], ['id' => 'DESC']);

        foreach ($processes as $process) {
            $filters[$process->getId()] = sprintf(
                '%d) Металл: %s, Метод: %s, Тип: %s, Создано: %s',
                $i,
                $process->getMetal()->getTitle(),
                $process->getMethod()->getTitle(),
                $process->getPeriod()->getTitle(),
                $process->getEndedAt()->format('d.m.Y H:i:s')
            );

            $i++;
        }

        return $filters;
    }

    /**
     * @return Prediction[]
     */
    public function getPredictionsByProcess(int $process): array
    {
        /** @var Process|null $process */
        $process = $this->processRepository->find($process);

        return is_null($process) ? [] : $this->predictionRepository->findByProcess($process);
    }
}
