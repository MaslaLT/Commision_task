<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Transaction;

use DateTime;
use Masel\CommissionTask\Currency\Currency;
use Masel\CommissionTask\Money\Money;
use Masel\CommissionTask\Operation\Operation;
use Masel\CommissionTask\User\User;

class Transaction
{
    protected $date;

    protected $user;

    protected $operation;

    public function __construct(DateTime $date, User $user, Operation $operation)
    {
        $this->date = $date;
        $this->user = $user;
        $this->operation = $operation;
    }

    public function date(): DateTime
    {
        return $this->date;
    }

    public function operation(): Operation
    {
        return $this->operation;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function userIsLegal(): bool
    {
        return $this->user->isLegal();
    }

    public function userIsNatural(): bool
    {
        return $this->user->isNatural();
    }

    public function operationIsCashIn(): bool
    {
        return $this->operation->isCashIn();
    }

    public function operationIsCashOut(): bool
    {
        return $this->operation->isCashOut();
    }

    public function operationMoneyAmount(): float
    {
        return $this->operation->getMoney()->getAmount();
    }

    public function operationCurrency(): Currency
    {
        return $this->operation()->getMoney()->getCurrency();
    }

    public function operationCurrencyDecimals(): int
    {
        return $this->operationCurrency()->getDecimals();
    }

    public function operationMoney(): Money
    {
        return $this->operation()->getMoney();
    }
}
