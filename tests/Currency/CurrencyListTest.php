<?php

namespace Masel\CommissionTask\Tests\Currency;

use Masel\CommissionTask\Currency\CurrencyList;
use Masel\CommissionTask\Currency\IsoCurrency;
use PHPUnit\Framework\TestCase;

class CurrencyListTest extends TestCase
{
    /**
     * @var CurrencyList
     */
    private $currencyList;

    protected function setUp()
    {
        $this->currencyList = new CurrencyList();
    }

    public function testAddIsoCurrency()
    {
        $isoCurrency = new IsoCurrency();
        $isoCurrency->setCode('TEST');
        $this->currencyList->addIsoCurrency($isoCurrency);
        $this->assertEquals($isoCurrency, $this->currencyList->getCurrency('TEST'));
    }
}
