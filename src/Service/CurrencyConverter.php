<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

class CurrencyConverter
{
    public function convertToEuro($currency, $amount): float
    {
        $conversionRate = $currency->getConversionRateToEuro();

        return $amount / $conversionRate;
    }

    public function convertFromEuro($currency, $amount): float
    {
        $conversionRate = $currency->getConversionRateToEuro();

        return $amount * $conversionRate;
    }
}
