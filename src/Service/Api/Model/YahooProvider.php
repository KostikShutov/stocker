<?php

declare(strict_types=1);

namespace App\Service\Api\Model;

use Exception;
use DateTimeInterface;
use App\Entity\Metal;
use App\Service\Api\ApiProvider;
use App\Service\Api\BaseProvider;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

final class YahooProvider implements ApiProvider
{
    private BaseProvider $baseProvider;

    public function __construct(BaseProvider $baseProvider)
    {
        $this->baseProvider = $baseProvider;
    }

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function provide(DateTimeInterface $start, DateTimeInterface $end): int
    {
        $start = (string) $start->getTimestamp();
        $end = (string) $end->getTimestamp();

        return $this->baseProvider->provide([
            Metal::METAL_GOLD      => "https://query1.finance.yahoo.com/v7/finance/download/GC=F?period1={$start}&period2={$end}&interval=1d&events=history",
            Metal::METAL_SILVER    => "https://query1.finance.yahoo.com/v7/finance/download/SI=F?period1={$start}&period2={$end}&interval=1d&events=history",
            Metal::METAL_PALLADIUM => "https://query1.finance.yahoo.com/v7/finance/download/PA=F?period1={$start}&period2={$end}&interval=1d&events=history",
            Metal::METAL_PLATINUM  => "https://query1.finance.yahoo.com/v7/finance/download/PL=F?period1={$start}&period2={$end}&interval=1d&events=history"
        ], $this->getId());
    }

    public function getId(): string
    {
        return self::PROVIDER_YAHOO;
    }
}
