<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use Masel\CommissionTask\Currency\CurrencyList;
use Masel\CommissionTask\Money\Money;
use Masel\CommissionTask\Operation\Operation;
use Masel\CommissionTask\Transaction\Transaction;
use Masel\CommissionTask\User\User;

class TransactionsBuilder
{
    /**
     * @var CurrencyList
     */
    protected $currencyList;

    /**
     * @var array
     */
    protected $transactions;

    public function __construct(CurrencyList $currencyList)
    {
        $this->currencyList = $currencyList;
    }

    public function fromCsv(CsvParser $transactionsArray): array
    {
        foreach ($transactionsArray->getParsedCsvRows() as $transaction) {
            $date = new \DateTime($transaction['0']);
            $currency = $this->currencyList->getCurrency($transaction['5']);
            $amountFloat = (float) $transaction['4'];
            $operation = new Operation($transaction['3'], new Money($currency, $amountFloat));
            $userIdInt = (int) $transaction['1'];
            $user = new User($userIdInt, $transaction['2']);

            $this->transactions[] = new Transaction($date, $user, $operation);
        }

        return $this->transactions;
    }
}
