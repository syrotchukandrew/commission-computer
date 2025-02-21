<?php

use App\Client\BinClient;
use App\Client\ExchangeRateClient;
use App\CommissionComputer\CommissionComputer;
use App\Service\CommissionCalculator;
use App\TransactionProvider\FileDataParser;
use App\Client\GuzzleClient;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$calculator = new CommissionCalculator(new BinClient(new GuzzleClient()));
$currencyRateProvider = new ExchangeRateClient(new GuzzleClient());
$transactionsProvider = new FileDataParser();
$commissionComputer = new CommissionComputer($currencyRateProvider, $transactionsProvider, $calculator);

try {
    $commissionComputer->computeCommissions();
} catch (JsonException $e) {
    var_dump('Error: ' . $e->getMessage());
}