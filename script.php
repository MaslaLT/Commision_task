<?php

require_once __DIR__.'/vendor/autoload.php';

use Masel\CommissionTask\Fees\CashIn;
use Masel\CommissionTask\Fees\CashOut;
use Masel\CommissionTask\Money\Money;
use Masel\CommissionTask\Resources\FixedExchangeRatesData;
use Masel\CommissionTask\Service\CommissionCalculator;
use Masel\CommissionTask\Service\CsvParser;
use Masel\CommissionTask\Service\FixedCurrencyListBuilder;
use Masel\CommissionTask\service\FixedExchangeRateProvider;
use Masel\CommissionTask\Service\MoneyExchanger;
use Masel\CommissionTask\Service\ScriptArgumentsProvider;
use Masel\CommissionTask\Service\TransactionsBuilder;
use Masel\CommissionTask\Service\DateManager;
use Masel\CommissionTask\Transaction\ThisWeekTransactions;
use Masel\CommissionTask\Resources\IsoCurrencyPredefinedData;

$scriptArguments = new ScriptArgumentsProvider(2);
$fileName = $scriptArguments->getArgument(1);
$parsedCsvArray = new CsvParser($fileName);

$currencyListBuilder = new FixedCurrencyListBuilder(new IsoCurrencyPredefinedData());
$CurrencyList = $currencyListBuilder->getFixedRateCurrenciesList();

$fixedExchangeRateProvider = new FixedExchangeRateProvider(FixedExchangeRatesData::getRates());
$moneyExchanger = new MoneyExchanger($fixedExchangeRateProvider);

$transactionsBuilder = new TransactionsBuilder($CurrencyList);

$FiveEuro = new Money($CurrencyList->getCurrency('eur'), 5);
$pointFiveEuro = new Money($CurrencyList->getCurrency('eur'), 0.5);
$thousandEuro = new Money($CurrencyList->getCurrency('eur'), 1000);

$cashIn = new CashIn(0.0003, $FiveEuro);
$cashOut = new CashOut(0.003, 3, $thousandEuro, $pointFiveEuro);


$transactions = $transactionsBuilder->fromCsv($parsedCsvArray);
$ThisWeekTransactions = new ThisWeekTransactions(new DateManager(), $moneyExchanger);
$commissionCalculator = new CommissionCalculator($cashIn, $cashOut, $moneyExchanger, $ThisWeekTransactions);

foreach ($transactions as $transaction) {
    $fee = $commissionCalculator->calculateFee($transaction);
    echo $fee.PHP_EOL;
}
