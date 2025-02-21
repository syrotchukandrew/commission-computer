<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class BinClient implements BinProviderInterface
{
    const string BIN_LIST_URL = 'https://lookup.binlist.net/';

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws JsonException
     * @throws GuzzleException
     */
    public function getCountryByBin(string $bin): ?string
    {
        $response = $this->httpClient->getResponse(self::BIN_LIST_URL . $bin);

        return $response['country']['alpha2'] ?? null;
    }
}