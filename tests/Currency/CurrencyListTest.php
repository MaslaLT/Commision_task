<?php

namespace Masel\CommissionTask\Tests\Currency;

use Masel\CommissionTask\Currency\CurrencyList;
use Masel\CommissionTask\Currency\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyListTest extends TestCase
{

    /**
     * @var CurrencyList
     */
    protected $currencyList;

    public function setUp()
    {
        $usd = new Currency(0.01, 1.1497, 'usd');
        $jpy = new Currency(1, 129.53, 'jpy');

        $this->currencyList = new CurrencyList();
        $this->currencyList->addCurrency($usd)->addCurrency($jpy);
    }

    public function testAddCurrency()
    {
        $lit = new Currency(0.001, 3.45, 'lit');
        $this->currencyList->addCurrency($lit);
        $this->assertSame($lit, $this->currencyList->getCurrency('lit'));
    }

    public function testGetCurrency()
    {
        $this->assertSame(
            $this->currencyList->getCurrency('USD'),
            $this->currencyList->getAll()['USD']
        );
    }

    public function testGetAll()
    {
        $all = $this->currencyList->getAll();
        $this->assertArrayHasKey('USD', $all);
        $this->assertArrayHasKey('JPY', $all);

        $this->assertSame(
            $this->currencyList->getCurrency('USD'),
            $this->currencyList->getAll()['USD']);

        $this->assertSame(
            $this->currencyList->getCurrency('JPY'),
            $this->currencyList->getAll()['JPY']);
    }

}
