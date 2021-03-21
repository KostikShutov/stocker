<?php

declare(strict_types=1);

namespace App\Service\Api\Model;

use Exception;
use DateTimeInterface;
use App\Entity\Metal;
use App\Service\Api\ApiProvider;
use App\Service\Api\BaseProvider;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

final class StooqProvider implements ApiProvider
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
        $start = $start->format('Ymd');
        $end = $end->format('Ymd');

        return $this->baseProvider->provide([
            Metal::METAL_GOLD      => "https://stooq.pl/q/d/l/?s=xauusd&d1={$start}&d2={$end}&i=d",
            Metal::METAL_SILVER    => "https://stooq.pl/q/d/l/?s=xagusd&d1={$start}&d2={$end}&i=d",
            Metal::METAL_PALLADIUM => "https://stooq.pl/q/d/l/?s=xpdusd&d1={$start}&d2={$end}&i=d",
            Metal::METAL_PLATINUM  => "https://stooq.pl/q/d/l/?s=xptusd&d1={$start}&d2={$end}&i=d"
        ], $this->getId());
    }

    public function getId(): string
    {
        return self::PROVIDER_STOOQ;
    }
}
