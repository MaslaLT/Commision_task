<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Currency;

class CurrencyList
{
    /**
     * List of currency objects.
     */
    private $currencyList = [];

    public function addCurrency(Currency $currency): CurrencyList
    {
        $this->currencyList[$currency->getCode()] = $currency;

        return $this;
    }

    /**
     * Array list of currency objects.
     */
    public function getAll(): array
    {
        return $this->currencyList;
    }

    /**
     * Returns currency object by its currency code.
     */
    public function getCurrency(string $code): Currency
    {
        return $this->currencyList[strtoupper($code)];
    }
}
