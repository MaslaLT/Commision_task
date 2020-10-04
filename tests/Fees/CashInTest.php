<?php

namespace Masel\CommissionTask\Tests\Fees;

use Masel\CommissionTask\Currency\IsoCurrency;
use Masel\CommissionTask\Fees\CashIn;
use Masel\CommissionTask\Money\Money;
use PHPUnit\Framework\TestCase;

class CashInTest extends TestCase
{
    private $cashIn;
    private $moneyFee;

    public function setUp()
    {
        $isoCurrency = new IsoCurrency();
        $this->moneyFee = new Money($isoCurrency, 99);
        $this->cashIn = new CashIn(0.5, $this->moneyFee);
    }

    public function testGetSingleOperationFee()
    {
        $this->assertEquals(0.5, $this->cashIn->getSingleOperationFee());
    }

    public function testGetMaxOperationFee()
    {
        $this->assertEquals($this->moneyFee, $this->cashIn->getMaxOperationFee());
    }

}
