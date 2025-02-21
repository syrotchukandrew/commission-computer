<?php

declare(strict_types=1);

namespace App\TransactionProvider;

use App\Model\Transaction;
use RuntimeException;

class FileDataParser implements TransactionProviderInterface
{
    public function getTransactions(): array
    {
        $filePath = $_ENV['FILE_PATH'];
        if (!file_exists($filePath)) {
            throw new RuntimeException("File not found: " . $filePath);
        }

        $handle = fopen($filePath, 'rb');

        if (!$handle) {
            throw new RuntimeException("File not found: " . $filePath);
        }

        $transactions = [];

        while (($line = fgets($handle)) !== false) {
            try {
                $transactionData = json_decode(trim($line), true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                throw new RuntimeException("Error decoding JSON: " . json_last_error_msg());
            }

            $transaction = new Transaction(
                $transactionData['bin'],
                (float) $transactionData['amount'],
                $transactionData['currency']
            );

            $transactions[] = $transaction;
        }

        fclose($handle);

        return $transactions;
    }
}