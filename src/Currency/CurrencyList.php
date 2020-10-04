<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Currency;

class CurrencyList
{
    /**
     * @var array
     */
    protected $currencies;

    /**
     * @throws \Exception
     */
    public function addIsoCurrency(IsoCurrency $currency)
    {
        if (!is_array($this->currencies)) {
            $this->currencies = [];
        }
        if (in_array($currency->getCode(), $this->currencies, true)) {
            throw new \Exception(sprintf('Currency name %s already exists. '.$currency->getCode()));
        }
        $this->currencies[$currency->getCode()] = $currency;
    }

    /**
     * @throws \Exception
     */
    public function getCurrency(string $code): Currency
    {
        /** @var Currency $currency */
        $upCaseCode = strtoupper($code);
        foreach ($this->currencies as $currency) {
            if ($upCaseCode === $currency->getCode()) {
                return $currency;
            }
        }

        throw new \Exception('No '.$code.' in list.');
    }
}
