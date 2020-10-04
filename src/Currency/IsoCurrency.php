<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Currency;

class IsoCurrency extends Currency
{
    /**
     * Iso Number of currency.
     *
     * @var int;
     */
    protected $isoNumber;

    /**
     * @throws \Exception
     */
    public function setIsoNumber(int $isoNumber)
    {
        if (3 !== strlen((string) $isoNumber)) {
            throw new \Exception(sprintf('Iso Number needs to be 3 digit. You provided "%s"', $isoNumber));
        }

        $this->isoNumber = $isoNumber;
    }

    public function getIsoNumber(): int
    {
        return $this->isoNumber;
    }
}
