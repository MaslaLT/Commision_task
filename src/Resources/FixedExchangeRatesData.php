<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Resources;

class FixedExchangeRatesData
{
    /**
     * @var array
     */
    private static $exchangeRates = [
        'USD' => [
            'EUR' => 1 / 1.1497,
            'JPY' => 129.53 * 1.1497,
        ],
        'EUR' => [
            'USD' => 1.1497,
            'JPY' => 129.53,
        ],
        'JPY' => [
            'USD' => 1.1497 / 129.53,
            'EUR' => 1 / 129.53,
        ],
    ];

    public static function getRates(): array
    {
        return self::$exchangeRates;
    }
}
