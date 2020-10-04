<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Resources;

class IsoCurrencyPredefinedData
{
    private $isoCurrencyData = [
        'USD' => [
            'number' => 840,
            'decimals' => 2,
            'name' => 'United States dollar',
        ],
        'EUR' => [
            'number' => 978,
            'decimals' => 2,
            'name' => 'Euro',
        ],
        'JPY' => [
            'number' => 392,
            'decimals' => 0,
            'name' => 'Japanese yen',
        ],
    ];

    public function getCurrenciesData(): array
    {
        return $this->isoCurrencyData;
    }
}
