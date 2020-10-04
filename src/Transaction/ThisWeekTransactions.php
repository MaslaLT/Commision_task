<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Transaction;

use Masel\CommissionTask\Currency\Currency;
use Masel\CommissionTask\Service\DateManager;
use Masel\CommissionTask\Service\MoneyExchanger;
use Masel\CommissionTask\User\User;

class ThisWeekTransactions
{
    /**
     * @var array
     */
    private $transactionsList;

    /**
     * @var DateManager
     */
    private $dateManager;

    /**
     * @var MoneyExchanger
     */
    private $moneyExchanger;

    public function __construct(DateManager $dateManager, MoneyExchanger $moneyExchanger)
    {
        $this->dateManager = $dateManager;
        $this->moneyExchanger = $moneyExchanger;
    }

    public function addTransaction(Transaction $transaction)
    {
        $this->transactionsList[] = $transaction;

        if (false === $this->isSameWeek($transaction)) {
            $this->resetTransactionsList($transaction);
        }
    }

    public function thisWeekTransactions(): array
    {
        return $this->transactionsList;
    }

    public function usersCashOutSum(User $user, Currency $currency): float
    {
        $usersTransactions = $this->usersTransactions($user);
        $transactionsSum = 0;

        /** @var Transaction $transaction */
        foreach ($usersTransactions as $transaction) {
            if ($transaction->operationIsCashOut()) {
                $convertedMoney = $this->moneyExchanger->exchange($transaction->operationMoney(), $currency);
                $transactionsSum += $convertedMoney->getAmount();
            }
        }

        return $transactionsSum;
    }

    private function usersTransactions(User $user): array
    {
        $userTransactions = [];

        /** @var Transaction $transaction */
        foreach ($this->thisWeekTransactions() as $transaction) {
            if ($transaction->user()->id() === $user->id()) {
                $userTransactions[] = $transaction;
            }
        }

        return $userTransactions;
    }

    public function usersCashOuts(User $user): int
    {
        return count($this->usersTransactions($user));
    }

    private function isSameWeek(Transaction $transaction): bool
    {
        if ($this->firstTransaction()) {
            $lastTransaction = $transaction;
        } else {
            $lastTransactionNumber = count($this->transactionsList) - 2;
            $lastTransaction = $this->transactionsList[$lastTransactionNumber];
        }

        return $this->dateManager->sameWeek($lastTransaction->date(), $transaction->date());
    }

    private function resetTransactionsList(Transaction $transaction)
    {
        unset($this->transactionsList);
        $this->transactionsList[] = $transaction;
    }

    private function firstTransaction()
    {
        if (count($this->transactionsList) <= 1) {
            return true;
        }

        return false;
    }
}
