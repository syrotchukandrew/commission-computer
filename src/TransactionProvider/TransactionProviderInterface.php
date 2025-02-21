<?php

declare(strict_types=1);

namespace App\TransactionProvider;

use App\Model\Transaction;

interface TransactionProviderInterface
{
    /**
     * @return Transaction[]
     */
    public function getTransactions(): array;
}