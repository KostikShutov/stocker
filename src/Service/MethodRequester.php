<?php

declare(strict_types=1);

namespace App\Service;

use Throwable;
use Symfony\Component\HttpClient\CurlHttpClient;

final class MethodRequester
{
    private CurlHttpClient $httpClient;

    private string $methodUrl;

    public function __construct(string $methodUrl)
    {
        $this->httpClient = new CurlHttpClient();
        $this->methodUrl = $methodUrl;
    }

    /**
     * @throws Throwable
     */
    public function request(string $method, array $query): array
    {
        return $this->httpClient->request('GET', $this->methodUrl . $method, [
            'query' => $query
        ])->toArray();
    }
}
