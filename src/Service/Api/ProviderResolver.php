<?php

declare(strict_types=1);

namespace App\Service\Api;

final class ProviderResolver
{
    /**
     * @var ApiProvider[]
     */
    private array $providers = [];

    public function getProvider(string $id): ?ApiProvider
    {
        return array_key_exists($id, $this->providers) ? $this->providers[$id] : null;
    }

    /**
     * @return ApiProvider[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    public function registerProvider(ApiProvider $provider): void
    {
        $this->providers[$provider->getId()] = $provider;
    }
}
