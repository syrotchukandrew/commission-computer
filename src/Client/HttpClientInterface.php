<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;

interface HttpClientInterface
{
    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getResponse(string $url, array $headers = [], string $method = 'GET'): array;
}
