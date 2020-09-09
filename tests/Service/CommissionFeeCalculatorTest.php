<?php

namespace Masel\CommissionTask\Tests\Service;

use Masel\CommissionTask\Currency\Currency;
use Masel\CommissionTask\Currency\CurrencyList;
use Masel\CommissionTask\Fees\CashIn;
use Masel\CommissionTask\Fees\CashOut;
use Masel\CommissionTask\Service\CommissionFeeCalculator;
use Masel\CommissionTask\Service\ScriptArgumentsManager;
use Masel\CommissionTask\Service\UserOperationsManager;
use PHPUnit\Framework\TestCase;

class CommissionFeeCalculatorTest extends TestCase
{
    private $commissionFeeCalculator;
    private $currencyList;
    private $userOperationsManager;

    public function setUp()
    {
        $this->userOperationsManager = new UserOperationsManager();
        $usd = new Currency(0.01, 1.1497, 'usd');
        $eur = new Currency(0.01, 1, 'eur');
        $jpy = new Currency(1, 129.53, 'jpy');
        $test = new Currency(0.001, 50, 'test');
        $currencyList = new CurrencyList();
        $this->currencyList = $currencyList
            ->addCurrency($usd)
            ->addCurrency($eur)
            ->addCurrency($test)
            ->addCurrency($jpy);

        $cashInFee = new CashIn(0.0003);
        $cashOutFee = new CashOut(0.003, 3, 1000, 0.5);
        $this->commissionFeeCalculator = new CommissionFeeCalculator(
            $cashInFee, $cashOutFee, $currencyList
        );
    }

    public function testFromCsv()
    {
        $argv = [
            '0' => __DIR__.'/../fullinput.csv',
        ];
        $scriptArgumentManager = new ScriptArgumentsManager($argv, 1);
        $this->commissionFeeCalculator->fromCsv($scriptArgumentManager, '0');

        $calculatedFees = $this->commissionFeeCalculator->getCalculatedFee();
        $expected = ['0.60', '3.00', '0.00', '0.06', '0.90', '0', '0.70', '0.30', '0.30', '5.0', '0.00', '0.00', '8612'];
        $this->assertEquals($expected, $calculatedFees);
    }

    /**
     * @param $userType
     * @param float $opAmount
     * @param $currency
     * @param $expectation
     *
     * @dataProvider dataProviderCalculateCashOutFeeNatural
     */
    public function testCalculateCashOutFeeNatural($userType, float $opAmount, $currency, $expectation)
    {
        $opCurrency = $this->currencyList->getCurrency($currency);

        $this->userOperationsManager->addThisWeakOperation(
            '1', 344.91, $this->currencyList->getCurrency('usd'), '1999-01-08', 'cash_out'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '1', $opAmount, $opCurrency, '1999-01-08', 'cash_out'
        );

        $userOperations = $this->userOperationsManager->getUserOpNumberAndAmountForThisWeak('1');

        $this->commissionFeeCalculator->calculateCashOutFee($userType, $opAmount, $opCurrency, $userOperations);
        $calculatedFee = $this->commissionFeeCalculator->getCalculatedFee();

        $this->assertEquals($expectation, $calculatedFee);
    }

    public function dataProviderCalculateCashOutFeeNatural()
    {
        return [
            'natural person 129530 JPY' => ['natural', 129530, 'Jpy', ['117']],
            'natural person 90671 JPY' => ['natural', 90671, 'Jpy', ['0']],
            'natural person 90672 JPY' => ['natural', 90672, 'Jpy', ['1']],
        ];
    }

    /**
     * @param $userType
     * @param $opAmount
     * @param $currency
     * @param $expectation
     *
     * @dataProvider dataProviderCalculateCashOutFeeLegal
     */
    public function testCalculateCashOutFeeLegal($userType, float $opAmount, $currency, $expectation)
    {
        $this->userOperationsManager->addThisWeakOperation(
            '1', 344.91, $this->currencyList->getCurrency('usd'), '1999-01-08', 'cash_out'
        );

        $userOperations = $this->userOperationsManager->getUserOpNumberAndAmountForThisWeak('1');

        $opCurrency = $this->currencyList->getCurrency($currency);
        $this->commissionFeeCalculator->calculateCashOutFee($userType, $opAmount, $opCurrency, $userOperations);
        $calculatedFee = $this->commissionFeeCalculator->getCalculatedFee();

        $this->assertEquals($expectation, $calculatedFee);
    }

    public function dataProviderCalculateCashOutFeeLegal()
    {
        return [
            'legal person 129530 JPY' => ['legal', 129530, 'Jpy', ['389']],
            'legal person 1 Eur' => ['legal', 1, 'Eur', ['0.5']],
            'legal person 1 Jpy' => ['legal', 1, 'Jpy', ['65']],
        ];
    }

    /**
     * @param $opAmount
     * @param $currency
     * @param $expectation
     *
     * @dataProvider dataProviderCalculateCashInFee
     */
    public function testCalculateCashInFee($opAmount, $currency, $expectation)
    {
        $opCurrency = $this->currencyList->getCurrency($currency);
        $this->commissionFeeCalculator->calculateCashInFee($opAmount, $opCurrency);
        $calculatedFee = $this->commissionFeeCalculator->getCalculatedFee();

        $this->assertEquals($expectation, $calculatedFee);
    }

    public function dataProviderCalculateCashInFee()
    {
        return [
            '10000 Eur cash in fee' => ['10000', 'EUR', ['3']],
            '200 Eur cash in fee' => ['200', 'EUR', ['0.06']],
            '1000000.00 Eur cash in fee' => ['1000000.00', 'EUR', ['5.00']],
            '555 USD cash in fee' => ['555', 'USD', ['0.17']],
            '100000 JPY cash in fee' => ['100000', 'JPY', ['5']],
            '100 TEST cash in fee' => ['100', 'TEST', ['0.030']],
        ];
    }

    public function testFreeCashOutLimitExceeded()
    {
        $this->userOperationsManager->addThisWeakOperation(
            '1', 1, $this->currencyList->getCurrency('usd'), '1999-01-08', 'cash_out'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '1', 1, $this->currencyList->getCurrency('usd'), '1999-01-08', 'cash_out'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '1', 1, $this->currencyList->getCurrency('usd'), '1999-01-08', 'cash_out'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '1', 1000, $this->currencyList->getCurrency('eur'), '1999-01-08', 'cash_out'
        );

        $userOperations = $this->userOperationsManager->getUserOpNumberAndAmountForThisWeak('1');

        $opCurrency = $this->currencyList->getCurrency('eur');
        $this->commissionFeeCalculator->calculateCashOutFee('natural', 1000, $opCurrency, $userOperations);
        $calculatedFee = $this->commissionFeeCalculator->getCalculatedFee();

        $this->assertEquals(['3'], $calculatedFee);
    }
}
