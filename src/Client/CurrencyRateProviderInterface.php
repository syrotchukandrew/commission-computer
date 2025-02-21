<?php

declare(strict_types=1);

namespace App\Client;

use JsonException;

interface CurrencyRateProviderInterface
{
    const string BASE_CURRENCY = 'EUR';

    /**
     * @throws JsonException
     */
    public function getCurrencyRates(array $transactions, string $baseCurrency = self::BASE_CURRENCY): array;
}
