<?php

declare(strict_types=1);

namespace Test\Client;

use App\Client\ExchangeRateClient;
use App\Client\HttpClientInterface;
use App\Model\Transaction;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use PHPUnit\Framework\TestCase;

class ExchangeRateClientTest extends TestCase
{
    private HttpClientInterface $httpClientMock;
    private ExchangeRateClient $exchangeRateClient;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->exchangeRateClient = new ExchangeRateClient($this->httpClientMock);
    }

    public function testGetExchangeRateSuccess(): void
    {
        $mockResponse = [
            'rates' => [
                'EUR' => 1.00,
                'JPY' => 0.0150,
            ],
        ];
        $expected = [
            'EUR' => 1.00,
            'JPY' => 0.0150,
        ];

        $this->httpClientMock
            ->expects($this->once())
            ->method('getResponse')
            ->willReturn($mockResponse);

        $result = $this->exchangeRateClient->getCurrencyRates($this->getTransactions());

        $this->assertEquals($expected, $result);
    }

    public function testGetExchangeRateThrowsJsonException(): void
    {
        $this->httpClientMock
            ->expects($this->once())
            ->method('getResponse')
            ->willThrowException($this->createStub(JsonException::class));

        $this->expectException(JsonException::class);

        $this->exchangeRateClient->getCurrencyRates($this->getTransactions());
    }

    public function testGetExchangeRateThrowsGuzzleException(): void
    {
        $this->httpClientMock
            ->expects($this->once())
            ->method('getResponse')
            ->willThrowException($this->createStub(GuzzleException::class));

        $this->expectException(GuzzleException::class);

        $this->exchangeRateClient->getCurrencyRates($this->getTransactions());
    }

    private function getTransactions(): array
    {
        $transactions[] = new Transaction('45717360', 100.00, 'EUR');
        $transactions[] = new Transaction('516793', 150.00, 'JPY');

        return $transactions;
    }
}