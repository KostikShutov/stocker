<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Metal;
use App\Entity\Method;
use App\Entity\Period;
use App\Entity\Prediction;
use App\Repository\PredictionRepository;

final class PredictionFilterService
{
    const CREATED_AT_FORMAT = 'd.m.Y H:i:s';

    private PredictionRepository $predictionRepository;

    private InformationFinder $informationFinder;

    public function __construct(
        PredictionRepository $predictionRepository,
        InformationFinder $informationFinder
    ) {
        $this->predictionRepository = $predictionRepository;
        $this->informationFinder = $informationFinder;
    }

    /**
     * @return string[]
     */
    public function getFilters(): array
    {
        $i = 1;
        $filters = [];
        $predictions = $this->predictionRepository->findPredictionsGroupedByCreatedAt();

        foreach ($predictions as $prediction) {
            $key = implode(',', [
                $metal = $prediction->getMetal()->getSlug(),
                $method = $prediction->getMethod()->getSlug(),
                $period = $prediction->getPeriod()->getSlug(),
                $createdAt = $prediction->getCreatedAt()->format(self::CREATED_AT_FORMAT)
            ]);

            $filters[$key] = sprintf(
                '%d) Металл: %s, Метод: %s, Тип: %s, Создано: %s',
                $i,
                $this->informationFinder->getInformationBySlug(Metal::class, $metal)->getTitle(),
                $this->informationFinder->getInformationBySlug(Method::class, $method)->getTitle(),
                $this->informationFinder->getInformationBySlug(Period::class, $period)->getTitle(),
                $createdAt
            );

            $i++;
        }

        return $filters;
    }

    /**
     * @return Prediction[]
     */
    public function getPredictionsByFilter(string $filter): array
    {
        $filter = explode(',', $filter);

        if (is_array($filter) && 4 === count($filter)) {
            /** @var Metal|null $metal */
            $metal = $this->informationFinder->getInformationBySlug(Metal::class, $filter[0]);
            /** @var Method|null $method */
            $method = $this->informationFinder->getInformationBySlug(Method::class, $filter[1]);
            /** @var Period|null $period */
            $period = $this->informationFinder->getInformationBySlug(Period::class, $filter[2]);
            $createdAt = date_create_from_format(self::CREATED_AT_FORMAT, $filter[3]);

            if ($metal && $method && $period && $createdAt) {
                return $this->predictionRepository->findByFilter($metal, $method, $period, $createdAt);
            }
        }

        return [];
    }
}
