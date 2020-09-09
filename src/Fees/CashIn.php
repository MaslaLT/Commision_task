<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Fees;

class CashIn
{
    /**
     * Fee for one operation.
     *
     * @var float
     */
    protected $singleOperationFee;

    /**
     * CashIn constructor.
     *
     * @param float
     */
    public function __construct(float $singleOperationFee = 0.0003)
    {
        $this->singleOperationFee = $singleOperationFee;

        return $this;
    }

    public function setSingleOperationFee(float $fee)
    {
        $this->singleOperationFee = $fee;

        return $this;
    }

    public function getSingleOperationFee(): float
    {
        return $this->singleOperationFee;
    }
}
