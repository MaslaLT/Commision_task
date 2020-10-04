<?php

namespace Masel\CommissionTask\Tests\Money;

use Masel\CommissionTask\Currency\IsoCurrency;
use Masel\CommissionTask\Money\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    private $money;
    private $currency;

    public function setUp()
    {
        $this->currency = new IsoCurrency();
        $this->money = new Money($this->currency, 55);
    }

    public function test__constructorThrowsException()
    {
        $this->expectException(\Exception::class);
        new Money($this->currency, -5);
    }

    public function testGetCurrency()
    {
        $this->money->setCurrency($this->currency);
        $this->assertEquals($this->currency, $this->money->getCurrency());
    }

    public function testGetAmount()
    {
        $this->money->setAmount(955);
        $this->assertEquals(955, $this->money->getAmount());
    }

}
