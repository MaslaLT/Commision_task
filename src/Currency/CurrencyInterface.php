<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Currency;

interface CurrencyInterface
{
    public function __construct(float $smallestCurrencyItem, float $conversionRateToEuro, string $code);

    public function getSmallestCurrencyItem();

    public function setSmallestCurrencyItem(float $smallestCurrencyItem);

    public function getConversionRateToEuro();

    public function setConversionRateToEuro(float $conversionRateToEuro);

    public function getCode();

    public function setCode(string $code);

    public function getDecimals();
}
