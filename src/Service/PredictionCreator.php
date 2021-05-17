<?php

declare(strict_types=1);

namespace App\Service;

use Throwable;
use App\Entity\Metal;
use App\Entity\Method;
use App\Entity\Period;
use App\Entity\Process;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class PredictionCreator
{
    private InformationFinder $informationFinder;

    private HttpClientInterface $httpClient;

    private JsonPredictionParser $jsonPredictionParser;

    private string $pythonUrl;

    public function __construct(
        InformationFinder $informationFinder,
        HttpClientInterface $httpClient,
        JsonPredictionParser $jsonPredictionParser,
        string $pythonUrl
    ) {
        $this->informationFinder = $informationFinder;
        $this->httpClient = $httpClient;
        $this->jsonPredictionParser = $jsonPredictionParser;
        $this->pythonUrl = $pythonUrl . '/method/';
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

        $content = $this->httpClient->request('GET', $this->pythonUrl . $method->getSlug(), [
            'query' => [
                'metal'    => $metal->getId(),
                'provider' => $options['provider'],
                'start'    => $options['start'],
                'end'      => $options['end'],
                'period'   => $period->getDays()
            ]
        ])->getContent();

        $this->jsonPredictionParser->parse($period, $metal, $method, $content);
    }
}
