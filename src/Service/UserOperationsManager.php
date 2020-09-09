<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use Masel\CommissionTask\Currency\Currency;

class UserOperationsManager
{
    /**
     * Stores all this week operations.
     *
     * @var array
     */
    protected $thisWeekOperations;

    /**
     * Variable stores CurrencyConverter service.
     *
     * @var \Masel\CommissionTask\Service\CurrencyConverter
     */
    protected $currencyConverter;

    /**
     * Variable stores DateManager service.
     *
     * @var \Masel\CommissionTask\Service\DateManager
     */
    protected $dateManager;

    /**
     * Is operation done on same week.
     *
     * @var bool
     */
    protected $sameWeek;

    public function __construct()
    {
        $this->currencyConverter = new CurrencyConverter();
        $this->dateManager = new DateManager();
        $this->sameWeek = false;
    }

    /**
     * Method checks if passed operation is done in same week like last
     * operation if operation done on same week it will be added
     * to $thisWeakOperations variable if not then $thisWeakOperations variable
     * will be reset.
     */
    public function addThisWeakOperation(string $userId, float $opAmount, Currency $opCurrency, string $date, string $opType)
    {
        $opAmount = $this->currencyConverter->convertToEuro($opCurrency, $opAmount);
        $lastOperationDate = $this->getLastOperationDate();

        if (false !== $lastOperationDate) {
            $this->sameWeek = $this->dateManager->sameWeek($lastOperationDate, $date);
        }

        if (false === $this->sameWeek) {
            $this->resetThisWeekOperations();
        }

        $this->addOperation($userId, $opAmount, $date, $opType);
    }

    /**
     * Method takes passed $userId variable and iterates $thisWeakOperations
     * array and searches all user saved operation.
     */
    public function getUserOpNumberAndAmountForThisWeak(string $userId): array
    {
        $casInOperations = 0;
        $casOutOperations = 0;
        $totalCasOutAmount = 0;

        foreach ($this->thisWeekOperations as $operation) {
            if ($operation['userId'] === $userId && 'cash_in' === $operation['opType']) {
                ++$casInOperations;
            }
            if ($operation['userId'] === $userId && 'cash_out' === $operation['opType']) {
                ++$casOutOperations;
                $totalCasOutAmount = $totalCasOutAmount + $operation['opAmountEur'];
            }
        }

        return [
            'cashInOperations' => $casInOperations,
            'cashOutOperations' => $casOutOperations,
            'totalCasOutAmount' => $totalCasOutAmount,
        ];
    }

    public function getThisWeekOperations(): array
    {
        return $this->thisWeekOperations;
    }

    private function getLastOperationDate()
    {
        if ($this->thisWeekOperations['0']['date']) {
            $lastOperation = end($this->thisWeekOperations);

            return $lastOperation['date'];
        } else {
            return false;
        }
    }

    private function resetThisWeekOperations()
    {
        $this->thisWeekOperations = [];
    }

    private function addOperation(string $userId, float $opAmount, string $date, string $opType)
    {
        $this->thisWeekOperations[] = [
            'userId' => $userId,
            'opAmountEur' => $opAmount,
            'date' => $date,
            'opType' => $opType,
        ];
    }
}
