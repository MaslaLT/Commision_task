<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use Masel\CommissionTask\Currency\Currency;
use Masel\CommissionTask\Money\Money;

class MoneyExchanger
{
    private $exchangeRateProvider;

    public function __construct(FixedExchangeRateProvider $exchangeRateProvider)
    {
        $this->exchangeRateProvider = $exchangeRateProvider;
    }

    public function exchange(Money $baseMoney, Currency $counterCurrency): Money
    {
        if ($this->isSameCurrency($baseMoney, $counterCurrency)) {
            return new Money($baseMoney->getCurrency(), $baseMoney->getAmount());
        }
        $conversionRate = $this->exchangeRateProvider->getRate($baseMoney->getCurrency(), $counterCurrency);

        return new Money($counterCurrency, $baseMoney->getAmount() * $conversionRate);
    }

    private function isSameCurrency(Money $baseMoney, Currency $currency): bool
    {
        if ($baseMoney->getCurrency() === $currency) {
            return true;
        }

        return false;
    }
}
