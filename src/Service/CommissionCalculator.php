<?php

declare(strict_types=1);

namespace App\Service;

use App\Client\BinProviderInterface;
use App\Model\Transaction;
use JsonException;

readonly class CommissionCalculator implements CommissionCalculatorInterface
{
    const array EUROPE_COUNTRIES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR','HR', 'HU',
        'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',
    ];

    public function __construct(private BinProviderInterface $binProvider)
    {
    }

    /**
     * @throws JsonException
     */
    public function calculateCommission(Transaction $transaction): float
    {
        $country = $this->binProvider->getCountryByBin($transaction->getBin());

        return in_array($country, self::EUROPE_COUNTRIES, true) ? 0.01 : 0.02;
    }
}