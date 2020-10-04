<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use Masel\CommissionTask\Currency\Currency;

class FixedExchangeRateProvider
{
    /**
     * @var array
     */
    protected $fixedRates;

    public function __construct(array $fixedRates)
    {
        $this->fixedRates = $fixedRates;
    }

    /**
     * @throws \Exception
     */
    public function getRate(Currency $baseCurrency, Currency $counterCurrency): float
    {
        if (false === array_key_exists($baseCurrency->getCode(), $this->fixedRates)) {
            throw new \Exception(sprintf('No exchange rates for "%s" currency.', $baseCurrency->getCode()));
        }

        if (false === array_key_exists($counterCurrency->getCode(), $this->fixedRates[$baseCurrency->getCode()])) {
            throw new \Exception(sprintf('"%s" cant be exchanged to "%s". Not supported currency.', $counterCurrency->getCode(), $baseCurrency->getCode()));
        }

        return $this->fixedRates[$baseCurrency->getCode()][$counterCurrency->getCode()];
    }
}
