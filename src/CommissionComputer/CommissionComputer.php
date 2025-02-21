<?php

declare(strict_types=1);

namespace App\CommissionComputer;

use App\Client\CurrencyRateProviderInterface;
use App\Model\Transaction;
use App\Service\CommissionCalculatorInterface;
use App\TransactionProvider\TransactionProviderInterface;
use JsonException;

class CommissionComputer
{
    private CurrencyRateProviderInterface $currencyRateProvider;
    private TransactionProviderInterface  $transactionsProvider;
    private CommissionCalculatorInterface $commissionCalculator;

    public function __construct(
        CurrencyRateProviderInterface $currencyRateProvider,
        TransactionProviderInterface  $transactionsProvider,
        CommissionCalculatorInterface $commissionCalculator,
    ) {
        $this->currencyRateProvider = $currencyRateProvider;
        $this->transactionsProvider = $transactionsProvider;
        $this->commissionCalculator = $commissionCalculator;
    }

    /**
     * @throws JsonException
     */
    public function computeCommissions(): void
    {
        $transactions = $this->transactionsProvider->getTransactions();
        $rates = $this->currencyRateProvider->getCurrencyRates($transactions);

        foreach ($transactions as $transaction) {
            if (array_key_exists($transaction->getCurrency(), $rates)) {
                $rate = $rates[$transaction->getCurrency()];
                $commissionPercent = $this->commissionCalculator->calculateCommission($transaction);

                $transaction->setCommission((float) $rate, $commissionPercent);

                $this->logInfo($transaction);
            }
        }
    }

    public function logInfo(Transaction $transaction): void
    {
        echo $transaction->getCommission();
        print "\n";
    }
}