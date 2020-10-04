<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Currency;

abstract class Currency
{
    const MAX_DECIMALS_DIGITS = 6;

    /**
     * @var int
     */
    protected $decimals;

    /**
     * IsoCurrency Code.
     *
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDecimals(): int
    {
        return $this->decimals;
    }

    /**
     * @throws \Exception
     */
    public function setDecimals(int $decimals)
    {
        if ($decimals < 0) {
            throw new \Exception('Decimal cant be negative number');
        }
        if ($decimals > $this::MAX_DECIMALS_DIGITS) {
            throw new \Exception('Decimals to long number.');
        }
        $this->decimals = $decimals;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = strtoupper($code);
    }
}
