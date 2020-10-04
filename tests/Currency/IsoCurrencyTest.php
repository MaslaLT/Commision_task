<?php

namespace Masel\CommissionTask\Tests\Currency;

use Masel\CommissionTask\Currency\IsoCurrency;
use PHPUnit\Framework\TestCase;

class IsoCurrencyTest extends TestCase
{

    /**
     * @var IsoCurrency
     */
    private $isoCurrency;

    public function setUp()
    {
        $this->isoCurrency = new IsoCurrency();
    }

    public function testSetName()
    {
        $this->isoCurrency->setName('TestName');
        $this->assertEquals('TestName', $this->isoCurrency->getName());
    }

    public function testSetDecimals()
    {
        $this->isoCurrency->setDecimals(3);
        $this->assertEquals(3, $this->isoCurrency->getDecimals());
    }

    public function testSetDecimalsThrowsExceptionNegativeNumber()
    {
        $this->expectException(\Exception::class);
        $this->isoCurrency->setDecimals(-1);
    }

    public function testSetDecimalsThrowsExceptionMaxLimitNumber()
    {
        $this->expectException(\Exception::class);

        $decimalsMaxLimit = $this->isoCurrency::MAX_DECIMALS_DIGITS;
        $toBigDecimalsNumber = $decimalsMaxLimit + 1;
        $this->isoCurrency->setDecimals($toBigDecimalsNumber);
    }

    public function testSetCode()
    {
        $this->isoCurrency->setCode('USD');
        $this->assertEquals('USD', $this->isoCurrency->getCode());
    }

    public function testSetCodeCapitals()
    {
        $this->isoCurrency->setCode('usd');
        $this->assertEquals('USD', $this->isoCurrency->getCode());
    }

    public function testSetIsoNumber()
    {
        $this->isoCurrency->setIsoNumber(999);
        $this->assertEquals(999, $this->isoCurrency->getIsoNumber());
    }

    public function testSetIsoNumberThrowsException()
    {
        $this->expectException(\Exception::class);
        $this->isoCurrency->setIsoNumber(9999);
    }
}
