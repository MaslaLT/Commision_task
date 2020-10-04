<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Operation;

use Masel\CommissionTask\Fees\CashIn;
use Masel\CommissionTask\Fees\CashOut;
use Masel\CommissionTask\Money\Money;

class Operation
{
    const CASH_IN = CashIn::CASH_IN;

    const CASH_OUT = CashOut::CASH_OUT;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Money
     */
    protected $money;

    public function __construct(string $type, Money $money)
    {
        $this->type = $type;
        $this->money = $money;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMoney()
    {
        return $this->money;
    }

    public function isCashIn(): bool
    {
        if (CashIn::CASH_IN === $this->type) {
            return true;
        } else {
            return false;
        }
    }

    public function isCashOut(): bool
    {
        if (CashOut::CASH_OUT === $this->type) {
            return true;
        } else {
            return false;
        }
    }
}
