<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Currency;

class Currency implements CurrencyInterface
{
    /**
     * Smallest currency item of this currency.
     *
     * As an example, Euro currency smallest item is one cent (1/100).
     * So $smallestCurrencyItem = 0.01.
     *
     * @var float
     */
    protected $smallestCurrencyItem;

    /**
     * Conversion rate of this currency in euro.
     *
     * @var float
     */
    protected $conversionRateToEuro;

    /**
     * Currency Code.
     *
     * As an example, Euro currency code is Eur.
     *
     * @var string
     */
    protected $code;

    public function __construct(float $smallestCurrencyItem, float $conversionRateToEuro, string $code)
    {
        $this->smallestCurrencyItem = $smallestCurrencyItem;
        $this->conversionRateToEuro = $conversionRateToEuro;
        $this->code = strtoupper($code);
    }

    public function getSmallestCurrencyItem(): float
    {
        return $this->smallestCurrencyItem;
    }

    public function setSmallestCurrencyItem(float $smallestCurrencyItem)
    {
        $this->smallestCurrencyItem = $smallestCurrencyItem;
    }

    public function getConversionRateToEuro(): float
    {
        return $this->conversionRateToEuro;
    }

    public function setConversionRateToEuro(float $conversionRateToEuro)
    {
        $this->conversionRateToEuro = $conversionRateToEuro;
    }

    public function getCode(): string
    {
        return strtoupper($this->code);
    }

    public function setCode(string $code)
    {
        $this->code = strtoupper($code);
    }

    public function getDecimals(): int
    {
        $currencySmallestItem = $this->smallestCurrencyItem;
        $decimal = 0;
        while ($currencySmallestItem < 1) {
            $currencySmallestItem = $currencySmallestItem * 10;
            ++$decimal;
        }

        return $decimal;
    }
}
