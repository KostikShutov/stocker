<?php

declare(strict_types=1);

namespace App\Service\Api;

use Exception;
use App\Entity\Metal;
use App\Entity\Stock;
use App\Service\StockPersister;
use App\Service\InformationFinder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

final class BaseProvider
{
    private HttpClientInterface $httpClient;

    private SerializerInterface $serializer;

    private InformationFinder $informationFinder;

    private StockPersister $stockPersister;

    public function __construct(
        HttpClientInterface $httpClient,
        SerializerInterface $serializer,
        InformationFinder $informationFinder,
        StockPersister $stockPersister
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->informationFinder = $informationFinder;
        $this->stockPersister = $stockPersister;
    }

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function provide(array $metals, string $provider): int
    {
        $knownQuotes = [];

        foreach ($metals as $prefix => $url) {
            $response = $this->httpClient->request('GET', $url);
            $knownQuotes = array_merge(
                $knownQuotes,
                $this->parseResponse($response, $prefix, $provider)
            );
        }

        return $this->stockPersister->persist($knownQuotes);
    }

    /**
     * @return Stock[]
     *
     * @throws ExceptionInterface
     */
    private function parseResponse(ResponseInterface $response, string $slug, string $provider): array
    {
        $stocks = [];
        /** @var Metal|null $metal */
        $metal = $this->informationFinder->getInformationBySlug(Metal::class, $slug);
        $items = $this->serializer->decode($response->getContent(), 'csv', [
            CsvEncoder::DELIMITER_KEY => ','
        ]);

        foreach ($items as $item) {
            $values = array_values($item);
            $stock = (new Stock())
                ->setMetal($metal)
                ->setDate(date_create($values[0]))
                ->setOpenPrice((float) $values[1])
                ->setHighPrice((float) $values[2])
                ->setLowPrice((float) $values[3])
                ->setClosePrice((float) $values[4])
                ->setProvider($provider);

            if ($stock->isEmpty()) {
                continue;
            }

            $stocks[] = $stock;
        }

        return $stocks;
    }
}
