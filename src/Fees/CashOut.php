<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Fees;

use Masel\CommissionTask\Money\Money;

class CashOut
{
    const CASH_OUT = 'cash_out';

    /**
     * @var float
     */
    protected $singleOperationFee;

    /**
     * @var int
     */
    protected $freeOperationsPerWeek;

    /**
     * @var Money
     */
    protected $freeCashOutPerWeek;

    /**
     * @var Money
     */
    protected $legalCashOutMin;

    public function __construct(float $singleOperationFee,
                                int $freeOperationsPerWeek,
                                Money $freeCashOutPerWeek,
                                Money $legalCashOutMin)
    {
        $this->singleOperationFee = $singleOperationFee;
        $this->freeOperationsPerWeek = $freeOperationsPerWeek;
        $this->freeCashOutPerWeek = $freeCashOutPerWeek;
        $this->legalCashOutMin = $legalCashOutMin;

        return $this;
    }

    public function getSingleOperationFee(): float
    {
        return $this->singleOperationFee;
    }

    public function getFreeOperationsPerWeek(): int
    {
        return $this->freeOperationsPerWeek;
    }

    public function getFreeCashOutPerWeek(): Money
    {
        return $this->freeCashOutPerWeek;
    }

    public function getLegalCashOutMin(): Money
    {
        return $this->legalCashOutMin;
    }
}
