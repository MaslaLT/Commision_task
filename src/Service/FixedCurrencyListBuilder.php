<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use Masel\CommissionTask\Currency\CurrencyList;
use Masel\CommissionTask\Currency\IsoCurrency;
use Masel\CommissionTask\Resources\IsoCurrencyPredefinedData;

class FixedCurrencyListBuilder
{
    /**
     * @var CurrencyList
     */
    protected $currencyList;

    private $fixedCurrencyRates;

    public function __construct(IsoCurrencyPredefinedData $fixedCurrencyRates)
    {
        $this->currencyList = new CurrencyList();
        $this->fixedCurrencyRates = $fixedCurrencyRates->getCurrenciesData();
    }

    /**
     * @throws \Exception
     */
    public function getFixedRateCurrenciesList(): CurrencyList
    {
        foreach ($this->fixedCurrencyRates as $key => $currencyData) {
            $currency = new IsoCurrency();
            $currency->setName($currencyData['name']);
            $currency->setCode($key);
            $currency->setDecimals($currencyData['decimals']);
            $currency->setIsoNumber($currencyData['number']);
            $this->currencyList->addIsoCurrency($currency);
        }

        return $this->currencyList;
    }
}
