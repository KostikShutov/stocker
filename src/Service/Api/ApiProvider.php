<?php

declare(strict_types=1);

namespace App\Service\Api;

use DateTimeInterface;

interface ApiProvider
{
    const PROVIDER_STOOQ = 'stooq';
    const PROVIDER_YAHOO = 'yahoo';

    public function provide(DateTimeInterface $start, DateTimeInterface $end): int;

    public function getId(): string;
}
