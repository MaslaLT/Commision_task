<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Fees;

use Masel\CommissionTask\Money\Money;

class CashIn
{
    const CASH_IN = 'cash_in';

    /**
     * @var float
     */
    private $singleOperationFee;

    /**
     * @var Money
     */
    private $maxOperationFee;

    public function __construct(float $singleOperationFee, Money $maxOperationFee)
    {
        $this->singleOperationFee = $singleOperationFee;
        $this->maxOperationFee = $maxOperationFee;

        return $this;
    }

    public function getSingleOperationFee(): float
    {
        return $this->singleOperationFee;
    }

    public function getMaxOperationFee(): Money
    {
        return $this->maxOperationFee;
    }
}
