<?php

namespace Test\Service;

use App\Client\BinProviderInterface;
use App\Model\Transaction;
use App\Service\CommissionCalculator;
use App\Service\CommissionCalculatorInterface;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private BinProviderInterface $binProviderMock;
    private CommissionCalculatorInterface $calculator;

    protected function setUp(): void
    {
        $this->binProviderMock = $this->createMock(BinProviderInterface::class);
        $this->calculator = new CommissionCalculator($this->binProviderMock);
    }

    #[TestWith(['DK', 0.01])]
    #[TestWith(['USA', 0.02])]
    #[TestWith([null, 0.02])]
    #[TestWith(['FAKE', 0.02])]
    public function testCalculateCommission(?string $country, float $expected)
    {
        $this->binProviderMock
            ->expects($this->once())
            ->method('getCountryByBin')
            ->willReturn($country);

        $result = $this->calculator->calculateCommission($this->getTransaction());

        $this->assertEquals($expected, $result);
    }

    private function getTransaction(): Transaction
    {
        return new Transaction('45717360', 100.00, 'EUR');
    }
}