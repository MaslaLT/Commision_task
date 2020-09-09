<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Fees;

class CashOut
{
    /**
     * Fee for single cash out operation.
     *
     * @var float
     */
    protected $singleOperationFee;

    /**
     * Set how much free cash outs can be done.
     *
     * @var int
     */
    protected $freeOperationsPerWeek;

    /**
     * Amount of free cash out sum per week in EUR.
     *
     * @var int
     */
    protected $freeCashOutPerWeek;

    /**
     * One operation minimal fee for legal person in EUR.
     *
     * @var float
     */
    protected $legalCashOutMin;

    public function __construct(float $singleOperationFee = 0.003,
                                int $freeOperationsPerWeek = 3,
                                int $freeCashOutPerWeek = 1000,
                                float $legalCashOutMin = 0.5)
    {
        $this->singleOperationFee = $singleOperationFee;
        $this->freeOperationsPerWeek = $freeOperationsPerWeek;
        $this->freeCashOutPerWeek = $freeCashOutPerWeek;
        $this->legalCashOutMin = $legalCashOutMin;

        return $this;
    }

    public function setSingleOperationFee(float $fee): CashOut
    {
        $this->singleOperationFee = $fee;

        return $this;
    }

    public function getSingleOperationFee(): float
    {
        return $this->singleOperationFee;
    }

    public function setFreeOperationsPerWeek(int $operations): CashOut
    {
        $this->freeOperationsPerWeek = $operations;

        return $this;
    }

    public function getFreeOperationsPerWeek(): int
    {
        return $this->freeOperationsPerWeek;
    }

    public function setFreeCashOutPerWeek(int $cashOutSum): CashOut
    {
        $this->freeCashOutPerWeek = $cashOutSum;

        return $this;
    }

    public function getFreeCashOutPerWeek(): int
    {
        return $this->freeCashOutPerWeek;
    }

    public function setLegalCashOutMin($legalCashOutMin)
    {
        $this->legalCashOutMin = $legalCashOutMin;
    }

    public function getLegalCashOutMin(): float
    {
        return $this->legalCashOutMin;
    }

    public function getCashOutFees(): array
    {
        return [
            'singleOperation' => $this->singleOperationFee,
            'freeOperationsPerWeek' => $this->freeOperationsPerWeek,
            'freeCashOutPerWeek' => $this->freeCashOutPerWeek,
            'legalCashOutMin' => $this->legalCashOutMin,
        ];
    }
}
