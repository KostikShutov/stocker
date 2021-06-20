<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeInterface;
use InvalidArgumentException;
use App\Service\Api\ProviderResolver;

final class StockDownloader
{
    private ProviderResolver $providerResolver;

    public function __construct(ProviderResolver $providerResolver)
    {
        $this->providerResolver = $providerResolver;
    }

    public function download(string $provider, DateTimeInterface $start, DateTimeInterface $end): int
    {
        $provider = $this->providerResolver->getProvider($provider);

        if (is_null($provider)) {
            throw new InvalidArgumentException('Unknown api provider id');
        }

        if ($start > $end) {
            throw new InvalidArgumentException('End date more than start date');
        }

        return $provider->provide($start, $end);
    }
}
