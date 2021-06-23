<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;
use Throwable;
use App\Entity\Process;
use App\Repository\StockRepository;
use Symfony\Component\HttpClient\Exception\TransportException;

final class PredictionCreator
{
    private StockRepository $stockRepository;

    private StockDownloader $stockDownloader;

    private MethodRequester $methodRequester;

    private JsonPredictionParser $jsonPredictionParser;

    public function __construct(
        StockRepository $stockRepository,
        StockDownloader $stockDownloader,
        MethodRequester $methodRequester,
        JsonPredictionParser $jsonPredictionParser
    ) {
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
        $metal = $process->getMetal();
        $options = $process->getOptions();
        $provider = $options['provider'];
        $last = $this->stockRepository->findLast($metal, $provider);

        try {
            $this->stockDownloader->download($provider, $last->getDate(), new DateTime());
        } catch (TransportException) {}

        $json = $this->methodRequester->request($process->getMethod()->getSlug(), [
            'metal'    => $metal->getId(),
            'provider' => $provider,
            'start'    => $options['start'],
            'end'      => $options['end'],
            'period'   => $process->getPeriod()->getDays()
        ]);

        $this->jsonPredictionParser->parse($process, reset($json));
    }
}
