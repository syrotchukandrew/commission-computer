<?php

declare(strict_types=1);

namespace App\Client;

use App\Model\Transaction;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class ExchangeRateClient implements CurrencyRateProviderInterface
{
    private HttpClientInterface $httpClient;

    const string EXCHANGE_RATE_URL = 'https://api.apilayer.com/exchangerates_data/latest?symbols=%s&base=%s';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws JsonException
     * @throws GuzzleException
     */
    public function getCurrencyRates(array $transactions, string $baseCurrency = self::BASE_CURRENCY): array
    {
        $currencyCodes = $this->getCurrenciesFromTransactions($transactions);
        $headers = [
            'Content-Type' => 'text/plain',
            'apikey' => $_ENV['CURRENCY_RATE_API_TOKEN'],
        ];
        $response = $this->httpClient->getResponse(
            sprintf(self::EXCHANGE_RATE_URL, implode(',', $currencyCodes), $baseCurrency),
            $headers
        );

        return $response['rates'];
    }

    private function getCurrenciesFromTransactions(array $transactions): array
    {
        $currencies = array_map(static function (Transaction $transaction) {
            return $transaction->getCurrency();
        }, $transactions);

        return array_unique($currencies);
    }
}