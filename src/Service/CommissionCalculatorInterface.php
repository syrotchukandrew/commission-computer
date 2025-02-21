<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Transaction;

interface CommissionCalculatorInterface
{
    public function calculateCommission(Transaction $transaction): float;
}
