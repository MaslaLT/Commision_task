<?php

use Masel\CommissionTask\Currency\Currency;
use Masel\CommissionTask\Currency\CurrencyList;
use Masel\CommissionTask\Fees\CashIn;
use Masel\CommissionTask\Fees\CashOut;
use Masel\CommissionTask\Service\CommissionFeeCalculator;
use Masel\CommissionTask\Service\ScriptArgumentsManager;

require_once __DIR__.'/vendor/autoload.php';

$arguments = new ScriptArgumentsManager($argv);

$cashInFee = new CashIn();
$cashOutFee = new CashOut();

$usd = new Currency(0.01, 1.1497, 'usd');
$eur = new Currency(0.01, 1, 'eur');
$jpy = new Currency(1, 129.53, 'jpy');
$currencyList = new CurrencyList();
$currencyList = $currencyList->addCurrency($usd)->addCurrency($eur)->addCurrency($jpy);

$commissionFeeCalculator = new CommissionFeeCalculator($cashInFee, $cashOutFee, $currencyList);
try {
    $commissionFeeCalculator->fromCsv($arguments, '1');
} catch (Exception $e) {
    die(PHP_EOL.$e->getMessage().PHP_EOL);
}
$commissionFeeCalculator->printCalculatedFee();
