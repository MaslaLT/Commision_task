<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use Masel\CommissionTask\Currency\Currency;
use Masel\CommissionTask\Fees\CashIn;
use Masel\CommissionTask\Fees\CashOut;
use Masel\CommissionTask\Money\Money;
use Masel\CommissionTask\Transaction\ThisWeekTransactions;
use Masel\CommissionTask\Transaction\Transaction;

class CommissionCalculator
{
    /**
     * @var MoneyExchanger
     */
    private $moneyExchanger;

    /**
     * @var CashIn
     */
    private $cashInFees;

    /**
     * @var CashOut
     */
    private $cashOutFees;

    /**
     * @var ThisWeekTransactions
     */
    private $weeksTransactions;

    public function __construct(CashIn $cashInFees,
                                CashOut $cashOutFees,
                                MoneyExchanger $moneyExchanger,
                                ThisWeekTransactions $weeksTransactions)
    {
        $this->cashInFees = $cashInFees;
        $this->cashOutFees = $cashOutFees;
        $this->moneyExchanger = $moneyExchanger;
        $this->weeksTransactions = $weeksTransactions;
    }

    /**
     * @throws \Exception
     */
    public function calculateFee(Transaction $transaction)
    {
        if ($transaction->operationIsCashIn()) {
            $convertedMaxOpFee = $this->moneyExchanger->exchange(
                $this->cashInFees->getMaxOperationFee(), $transaction->operationCurrency()
            );

            return $this->calculateCashInFee($transaction, $convertedMaxOpFee);
        }

        if ($transaction->operationIsCashOut() && $transaction->userIsLegal()) {
            $convertedLegalCashOutMinFee = $this->moneyExchanger->exchange(
                $this->cashOutFees->getLegalCashOutMin(), $transaction->operationCurrency()
            );

            return $this->calculateCashOutFeeLegal($transaction, $convertedLegalCashOutMinFee);
        }

        if ($transaction->operationIsCashOut() && $transaction->userIsNatural()) {
            $convertedFreeCashOutSum = $this->moneyExchanger->exchange(
                $this->cashOutFees->getFreeCashOutPerWeek(), $transaction->operationCurrency()
            );

            return $this->calculateCashOutFeeNatural($transaction, $convertedFreeCashOutSum);
        }

        throw new \Exception('Operation type is not supported. '.$transaction->operation()->getType());
    }

    private function calculateCashInFee(Transaction $transaction, Money $convertedMaxOpFee): string
    {
        $this->addTransactionToThisWeekTransactionsList($transaction);
        $singleOpFee = $this->applySingleCashInOpFee($transaction->operationMoneyAmount());
        $ceiledSingleOpFee = $this->ceil($singleOpFee, $transaction->operationCurrency());

        if ($this->calculatedFeeExceedsMaxFee($ceiledSingleOpFee, $convertedMaxOpFee)) {
            $ceiledSingleOpFee = $convertedMaxOpFee->getAmount();
        }

        return $this->numberFormat($ceiledSingleOpFee, $transaction->operationCurrencyDecimals());
    }

    private function calculateCashOutFeeLegal(Transaction $transaction, Money $convertedLegalCashOutMinFee): string
    {
        $this->addTransactionToThisWeekTransactionsList($transaction);
        $singleOpFee = $this->applySingleCashOutOpFee($transaction->operationMoneyAmount());
        $ceiledSingleOpFee = $this->ceil($singleOpFee, $transaction->operationCurrency());
        if ($this->calculatedFeeLessThenMinimalFee($ceiledSingleOpFee, $convertedLegalCashOutMinFee)) {
            $ceiledSingleOpFee = $convertedLegalCashOutMinFee->getAmount();
        }

        return $this->numberFormat($ceiledSingleOpFee, $transaction->operationCurrencyDecimals());
    }

    private function calculateCashOutFeeNatural(Transaction $transaction, Money $convertedFreeCashOutSum): string
    {
        $this->addTransactionToThisWeekTransactionsList($transaction);
        $UsersThisWeekCashOutSum = $this->weeksTransactions->usersCashOutSum($transaction->user(), $transaction->operationCurrency());
        $UsersCashOutsNumber = $this->weeksTransactions->usersCashOuts($transaction->user());
        $operationMoney = $transaction->operationMoneyAmount();

        if ($this->userCashOutsNumberExceedsMaxWeekLimit($UsersCashOutsNumber)) {
            $convertedFreeCashOutSum->setAmount(0);
        }

        if ($this->freeCashOutMoneyLeft($transaction, $convertedFreeCashOutSum)) {
            $freeCashOutSumLeft = $UsersThisWeekCashOutSum - $convertedFreeCashOutSum->getAmount();
            $operationMoney = (max($freeCashOutSumLeft, 0));
        }

        $singleOpFee = $this->applySingleCashOutOpFee($operationMoney);
        $ceiledSingleOpFee = $this->ceil($singleOpFee, $transaction->operationCurrency());

        return $this->numberFormat($ceiledSingleOpFee, $transaction->operationCurrencyDecimals());
    }

    private function ceil(float $amount, Currency $currency)
    {
        $decimal = 0;
        $multiplier = 1;

        while ($decimal < $currency->getDecimals()) {
            $multiplier = $multiplier * 10;
            ++$decimal;
        }

        return ceil($amount * $multiplier) / $multiplier;
    }

    private function numberFormat($number, $decimals): string
    {
        return number_format($number, $decimals, '.', '');
    }

    private function addTransactionToThisWeekTransactionsList(Transaction $transaction)
    {
        $this->weeksTransactions->addTransaction($transaction);
    }

    private function applySingleCashInOpFee(float $moneyAmount): float
    {
        return $moneyAmount * $this->cashInFees->getSingleOperationFee();
    }

    private function applySingleCashOutOpFee(float $moneyAmount): float
    {
        return $moneyAmount * $this->cashOutFees->getSingleOperationFee();
    }

    private function calculatedFeeExceedsMaxFee(float $calculatedFee, Money $convertedMaxFee)
    {
        if ($calculatedFee > $convertedMaxFee->getAmount()) {
            return true;
        }

        return false;
    }

    private function calculatedFeeLessThenMinimalFee(float $calculatedFee, Money $convertedLegalCashOutMinFee)
    {
        if ($calculatedFee < $convertedLegalCashOutMinFee->getAmount()) {
            return true;
        }

        return false;
    }

    private function userCashOutsNumberExceedsMaxWeekLimit($UsersCashOutsNumber)
    {
        if ($UsersCashOutsNumber > $this->cashOutFees->getFreeOperationsPerWeek()) {
            return true;
        }

        return false;
    }

    private function freeCashOutMoneyLeft(Transaction $transaction, $convertedFreeCashOutSum)
    {
        $usersThisWeekCashOutSum = $this->weeksTransactions->usersCashOutSum($transaction->user(), $transaction->operationCurrency());
        if ($usersThisWeekCashOutSum - $transaction->operationMoneyAmount() <= $convertedFreeCashOutSum->getAmount()) {
            return true;
        }

        return false;
    }
}
