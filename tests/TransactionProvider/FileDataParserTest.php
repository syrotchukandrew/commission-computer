<?php

declare(strict_types=1);

namespace Test\TransactionProvider;

use App\Model\Transaction;
use App\TransactionProvider\FileDataParser;
use App\TransactionProvider\TransactionProviderInterface;
use PHPUnit\Framework\TestCase;

class FileDataParserTest extends TestCase
{
    private TransactionProviderInterface $parser;
    protected function setUp(): void
    {
        $this->parser = new FileDataParser();
    }

    public function testGetTransactions(): void
    {
        $result = $this->parser->getTransactions();
        $this->assertEquals($this->getTransactions(), $result);
    }

    private function getTransactions(): array
    {
        $transactions[] = new Transaction('45717360', 100.00, 'EUR');
        $transactions[] = new Transaction('516793', 50.00, 'USD');
        $transactions[] = new Transaction('45417360', 10000.00, 'JPY');

        return $transactions;
    }
}