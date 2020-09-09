<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use Masel\CommissionTask\Currency\Currency;
use Masel\CommissionTask\Currency\CurrencyList;
use Masel\CommissionTask\Fees\CashIn;
use Masel\CommissionTask\Fees\CashOut;

class CommissionFeeCalculator
{
    /**
     * @var \Masel\CommissionTask\Currency\CurrencyList
     */
    protected $currencyList;

    /**
     * @var \Masel\CommissionTask\Fees\CashOut
     */
    protected $cashOutFees;

    /**
     * @var \Masel\CommissionTask\Fees\CashIn
     */
    protected $cashInFees;

    /**
     * @var \Masel\CommissionTask\Service\CurrencyConverter
     */
    protected $currencyConverter;

    /**
     * @var \Masel\CommissionTask\Service\CsvParser
     */
    protected $parsedCsvFile;

    /**
     * Array holds all calculated fees.
     *
     * @var array
     */
    protected $fee;

    public function __construct(CashIn $cashInFee, CashOut $cashOutFee, CurrencyList $currencyList)
    {
        $this->currencyConverter = new CurrencyConverter();
        $this->cashInFees = $cashInFee;
        $this->cashOutFees = $cashOutFee;
        $this->currencyList = $currencyList;
    }

    private function commissionFeeCalculate(string $userType, string $opType, float $opAmount, Currency $opCurrency, array $userOperations)
    {
        if ('cash_in' === $opType) {
            $this->calculateCashInFee($opAmount, $opCurrency);
        }

        if ('cash_out' === $opType) {
            $this->calculateCashOutFee($userType, $opAmount, $opCurrency, $userOperations);
        }
    }

    public function calculateCashInFee(float $opAmount, Currency $opCurrency)
    {
        $fee = $opAmount * $this->cashInFees->getSingleOperationFee();
        $fee = $fee * (1 / $opCurrency->getSmallestCurrencyItem());
        $fee = ceil($fee);
        $fee = $fee / (1 / $opCurrency->getSmallestCurrencyItem());
        $fee = $fee <= 5 ? $fee : 5;

        $this->fee[] = number_format((float) $fee, $opCurrency->getDecimals(), '.', '');
    }

    public function calculateCashOutFee(string $userType, float $opAmount, Currency $opCurrency, array $userOperations)
    {
        $userOperationsInCurrentCurrency = $this->currencyConverter->convertFromEuro($opCurrency, $userOperations['totalCasOutAmount']);
        $currencySmallestItem = $opCurrency->getSmallestCurrencyItem();
        $currencyDecimals = $opCurrency->getDecimals();

        if ('legal' === $userType) {
            $minimalFee = $this->cashOutFees->getLegalCashOutMin() * $opCurrency->getConversionRateToEuro();
            $fee = $opAmount * $this->cashOutFees->getSingleOperationFee();
            $fee = $fee * (1 / $opCurrency->getSmallestCurrencyItem());
            $fee = ceil($fee);
            $fee = $fee / (1 / $opCurrency->getSmallestCurrencyItem());
            $fee = $fee <= $minimalFee ? $minimalFee : $fee;

            $this->fee[] = number_format((float) $fee, $opCurrency->getDecimals(), '.', '');
        }

        if ('natural' === $userType) {
            $freeCashOutSum = $this->currencyConverter->convertFromEuro($opCurrency, $this->cashOutFees->getFreeCashOutPerWeek());
            $freeOperationsLimit = $this->cashOutFees->getFreeOperationsPerWeek();

            if ($userOperations['cashOutOperations'] > $freeOperationsLimit) {
                $freeCashOutSum = 0;
            }
            if ($userOperationsInCurrentCurrency - $opAmount <= $freeCashOutSum) {
                $opAmount = $userOperationsInCurrentCurrency - $freeCashOutSum;
                $opAmount = max($opAmount, 0);
            }
            $fee = $opAmount * $this->cashOutFees->getSingleOperationFee();
            $fee = $fee * (1 / $currencySmallestItem);
            $fee = ceil($fee);
            $fee = $fee / (1 / $currencySmallestItem);

            $this->fee[] = number_format((float) $fee, $currencyDecimals, '.', '');
        }
    }

    public function printCalculatedFee()
    {
        foreach ($this->fee as $fee) {
            echo $fee.PHP_EOL;
        }
    }

    public function getCalculatedFee(): array
    {
        return $this->fee;
    }

    public function fromCsv(ScriptArgumentsManager $scriptArguments, string $argumentId)
    {
        $csvParser = new CsvParser($scriptArguments, $argumentId);
        $this->parsedCsvFile = $csvParser->getParsedCsvRows();
        $thisWeakOperations = new UserOperationsManager();

        foreach ($this->parsedCsvFile as $row) {
            $date = $row['0'];
            $userId = $row['1'];
            $userType = $row['2'];
            $opType = $row['3'];
            $opAmount = $row['4'];
            $opCurrency = $this->currencyList->getCurrency($row['5']);

            $thisWeakOperations->addThisWeakOperation((string) $userId, (float) $opAmount, $opCurrency, (string) $date, (string) $opType);
            $userOperations = $thisWeakOperations->getUserOpNumberAndAmountForThisWeak($userId);

            $this->commissionFeeCalculate((string) $userType, (string) $opType, (float) $opAmount, $opCurrency, $userOperations);
        }
    }
}
