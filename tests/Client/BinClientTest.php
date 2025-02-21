<?php

declare(strict_types=1);

namespace Test\Client;

use App\Client\BinClient;
use App\Client\HttpClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class BinClientTest extends TestCase
{
    const string BIN_LIST_URL = 'https://lookup.binlist.net/';
    const string BIN_NUMBER = '45717360';
    const string URL = self::BIN_LIST_URL . self::BIN_NUMBER;
    const string COUNTRY_CODE = 'DE';

    private HttpClientInterface $httpClientMock;
    private BinClient $binClient;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->binClient = new BinClient($this->httpClientMock);
    }

    #[TestWith(['DK', ['country' => ['alpha2' => 'DK']]])]
    #[TestWith([null, ['country' => []]])]
    public function testGetCountryByBin(?string $expected, array $mockResponse): void
    {
        $this->httpClientMock
            ->expects($this->once())
            ->method('getResponse')
            ->with(self::URL)
            ->willReturn($mockResponse);

        $result = $this->binClient->getCountryByBin(self::BIN_NUMBER);

        $this->assertEquals($expected, $result);
    }

    public function testGetCountryByBinThrowsJsonException(): void
    {
        $this->httpClientMock
            ->expects($this->once())
            ->method('getResponse')
            ->with(self::URL)
            ->willThrowException(new JsonException('Invalid JSON'));

        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Invalid JSON');

        $this->binClient->getCountryByBin(self::BIN_NUMBER);
    }

    public function testGetCountryByBinThrowsGuzzleException(): void
    {
        $this->httpClientMock
            ->expects($this->once())
            ->method('getResponse')
            ->with(self::URL)
            ->willThrowException($this->createStub(GuzzleException::class));

        $this->expectException(GuzzleException::class);

        $this->binClient->getCountryByBin(self::BIN_NUMBER);
    }
}