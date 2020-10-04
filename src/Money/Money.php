<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Money;

use Masel\CommissionTask\Currency\Currency;

class Money
{
    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @var float;
     */
    protected $amount;

    /**
     * @throws \Exception
     */
    public function __construct(Currency $currency, float $amount)
    {
        $this->currency = $currency;

        if (0 > $amount) {
            throw new \Exception('Amount must be grater then 0. '.$amount.' given.');
        }

        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }
}
