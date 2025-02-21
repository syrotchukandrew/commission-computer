<?php

namespace App\Model;

class Transaction
{
    private string $bin;
    private float $amount;
    private string $currency;
    private string $commission;

    public function __construct(string $bin, float $amount, string $currency)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCommission(): string
    {
        return $this->commission;
    }

    public function setCommission(float $rate, float $commissionPercent): void
    {
        if (!$rate) {
            $rate = 1.00;
        }
        $amountInEuro = $this->getAmount() / $rate;

        $this->commission = ((string) (ceil($commissionPercent * $amountInEuro * 100)/100));
    }
}