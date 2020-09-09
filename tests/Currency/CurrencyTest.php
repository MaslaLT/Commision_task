<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Tests\Currency;

use Masel\CommissionTask\Currency\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * @var Currency
     */
    private $currency;

    public function setUp()
    {
        $this->currency = new Currency(0.01, 1.259, 'TEST');
    }

    public function testGetSmallestCurrencyItem()
    {
        $this->assertEquals(0.01, $this->currency->getSmallestCurrencyItem());
    }

    public function testSetSmallestCurrencyItem()
    {
        $this->currency->setSmallestCurrencyItem(0.1);
        $this->assertEquals(0.1, $this->currency->getSmallestCurrencyItem());
    }

    public function testGetConversionRateToEuro()
    {
        $this->assertEquals(1.259, $this->currency->getConversionRateToEuro());
    }

    public function testSetConversionRateToEuro()
    {
        $this->currency->setConversionRateToEuro(10);
        $this->assertEquals(10, $this->currency->getConversionRateToEuro());
    }

    public function testGetCode()
    {
        $this->assertEquals('TEST', $this->currency->getCode());
    }

    public function testSetCode()
    {
        $this->currency->setCode('TESTSETCODE');
        $this->assertEquals('TESTSETCODE', $this->currency->getCode());
    }

    public function testSetCodeReturnsUpperCase()
    {
        $this->currency->setCode('TestSetCode1');
        $this->assertEquals('TESTSETCODE1', $this->currency->getCode());
    }

    /**
     * @param float $data
     * @param int $expectation
     *
     * @dataProvider dataProviderForGetDecimalsTesting
     */
    public function testGetDecimals(float $data, int $expectation)
    {
        $this->currency->setSmallestCurrencyItem($data);
        $this->assertEquals($expectation, $this->currency->getDecimals());
    }

    public function dataProviderForGetDecimalsTesting(): array
    {
        return [
            'currency dont have cents' => [1, 0],
            'currency smallest item is 0.1' => [0.1, 1],
            'currency smallest item is 0.01' => [0.01, 2],
            'currency smallest item is 0.001' => [0.001, 3],
        ];
    }
}
