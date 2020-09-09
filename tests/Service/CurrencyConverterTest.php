<?php

namespace Masel\CommissionTask\Tests\Service;

use Masel\CommissionTask\Service\CurrencyConverter;
use Masel\CommissionTask\Currency\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyConverterTest extends TestCase
{

    /**
     * @var CurrencyConverter
     */
    protected $currencyConverter;

    /**
     * @var Currency
     */
    protected $currency;

    public function setUp()
    {
        $this->currencyConverter = new CurrencyConverter();
        $this->currency = new Currency(0.05, 15, 'test');
    }

    public function testConvertFromEuro()
    {
        $converted = $this->currencyConverter->convertFromEuro($this->currency, 1000);
        $this->assertEquals(15000, $converted);
    }

    public function testConvertToEuro()
    {
        $converted = $this->currencyConverter->convertToEuro($this->currency, 1500);
        $this->assertEquals(100, $converted);
    }

}
