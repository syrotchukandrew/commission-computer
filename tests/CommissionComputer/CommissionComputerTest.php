<?php

declare(strict_types=1);

namespace Test\CommissionComputer;

use App\Client\CurrencyRateProviderInterface;
use App\CommissionComputer\CommissionComputer;
use App\Model\Transaction;
use App\Service\CommissionCalculatorInterface;
use App\TransactionProvider\TransactionProviderInterface;
use PHPUnit\Framework\TestCase;

class CommissionComputerTest extends TestCase
{
    private CurrencyRateProviderInterface $currencyRateProvider;
    private TransactionProviderInterface $transactionsProvider;
    private CommissionCalculatorInterface $commissionCalculator;
    private CommissionComputer $commissionComputer;

    protected function setUp(): void
    {
        $this->currencyRateProvider = $this->createMock(CurrencyRateProviderInterface::class);
        $this->transactionsProvider = $this->createMock(TransactionProviderInterface::class);
        $this->commissionCalculator = $this->createMock(CommissionCalculatorInterface::class);

        $this->commissionComputer = $this
            ->getMockBuilder(CommissionComputer::class)
            ->setConstructorArgs([$this->currencyRateProvider, $this->transactionsProvider, $this->commissionCalculator])
            ->onlyMethods(['logInfo'])
            ->getMock();
    }

    public function testComputeCommissions()
    {

        $this->currencyRateProvider
            ->expects(self::once())
            ->method('getCurrencyRates')
            ->willReturn($this->getRates())
        ;

        $this->commissionCalculator
            ->expects(self::exactly(3))
            ->method('calculateCommission')
            ->willReturn(0.01)
        ;

        $transaction = $this->getTransactions();
        $this->transactionsProvider
            ->expects(self::once())
            ->method('getTransactions')
            ->willReturn($transaction)
        ;

        $this->commissionComputer->computeCommissions();

        $this->assertEquals($transaction[0]->getCommission(), 1);
        $this->assertEquals($transaction[1]->getCommission(), 0.56);
        $this->assertEquals($transaction[2]->getCommission(), 0.67);
    }

    private function getTransactions(): array
    {
        $transactions[] = new Transaction('45717360', 100.00, 'EUR');
        $transactions[] = new Transaction('516793', 50.00, 'USD');
        $transactions[] = new Transaction('45417360', 10000.00, 'JPY');

        return $transactions;
    }

    private function getRates(): array
    {
        return [
            'EUR' => 1.00,
            'USD' => 0.9,
            'JPY' => 150
        ];
    }
}