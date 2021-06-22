<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;
use Throwable;
use App\Entity\Metal;
use App\Entity\Method;
use App\Entity\Period;
use App\Entity\Process;
use App\Repository\StockRepository;
use Symfony\Component\HttpClient\Exception\TransportException;

final class PredictionCreator
{
    private InformationFinder $informationFinder;

    private StockRepository $stockRepository;

    private StockDownloader $stockDownloader;

    private MethodRequester $methodRequester;

    private JsonPredictionParser $jsonPredictionParser;

    public function __construct(
        InformationFinder $informationFinder,
        StockRepository $stockRepository,
        StockDownloader $stockDownloader,
        MethodRequester $methodRequester,
        JsonPredictionParser $jsonPredictionParser
    ) {
        $this->informationFinder = $informationFinder;
        $this->stockRepository = $stockRepository;
        $this->stockDownloader = $stockDownloader;
        $this->methodRequester = $methodRequester;
        $this->jsonPredictionParser = $jsonPredictionParser;
    }

    /**
     * @throws Throwable
     */
    public function create(Process $process): void
    {
        /** @var Period|null $period */
        $period = $this->informationFinder->getInformationBySlug(Period::class, $process->getPeriod());
        /** @var Metal|null $metal */
        $metal = $this->informationFinder->getInformationBySlug(Metal::class, $process->getMetal());
        /** @var Method|null $method */
        $method = $this->informationFinder->getInformationBySlug(Method::class, $process->getMethod());
        $options = $process->getOptions();
        $provider = $options['provider'];
        $last = $this->stockRepository->findLast($metal, $provider);

        try {
            $this->stockDownloader->download($provider, $last->getDate(), new DateTime());
        } catch (TransportException) {}

        $json = $this->methodRequester->request($method->getSlug(), [
            'metal'    => $metal->getId(),
            'provider' => $provider,
            'start'    => $options['start'],
            'end'      => $options['end'],
            'period'   => $period->getDays()
        ]);

        $this->jsonPredictionParser->parse($period, $metal, $method, reset($json));
    }
}
