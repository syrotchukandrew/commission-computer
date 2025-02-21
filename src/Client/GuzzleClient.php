<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class GuzzleClient implements HttpClientInterface
{
    private Client $client;
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getResponse(string $url, array $headers = [], string $method = 'GET'): array
    {
        try {
            $response = $this->client->request(
                $method,
                $url,
                ['headers' => $headers]
            );
        } catch (GuzzleException $e) {
            var_dump('Error: ' . $e->getMessage());
            throw $e;
        }

        if ($response->getStatusCode() !== 200) {
            var_dump('StatusCode: ' . $response->getStatusCode());
        }

        $body = $response->getBody()->getContents();

        return json_decode($body, true, 512, JSON_THROW_ON_ERROR|JSON_OBJECT_AS_ARRAY);
    }
}