<?php

namespace Masel\CommissionTask\Tests\Operation;

use Masel\CommissionTask\Currency\IsoCurrency;
use Masel\CommissionTask\Fees\CashIn;
use Masel\CommissionTask\Fees\CashOut;
use Masel\CommissionTask\Money\Money;
use Masel\CommissionTask\Operation\Operation;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase
{

    /**
     * @var Operation
     */
    private $cashInOp;

    /**
     * @var Operation
     */
    private $cashOutOp;

    /**
     * @var Money
     */
    private $money;

    public function setUp()
    {
        $currency = new IsoCurrency();
        $this->money = new Money($currency, 68);
        $this->cashInOp = new Operation(CashIn::CASH_IN, $this->money);
        $this->cashOutOp = new Operation(CashOut::CASH_OUT, $this->money);
    }

    public function testIsCashOut()
    {
        $this->assertEquals(true, $this->cashOutOp->isCashOut());
        $this->assertEquals(false, $this->cashInOp->isCashOut());
    }

    public function testIsCashIn()
    {
        $this->assertEquals(true, $this->cashInOp->isCashIn());
        $this->assertEquals(false, $this->cashOutOp->isCashIn());
    }

    public function testGetType()
    {
        $this->assertEquals('cash_in', $this->cashInOp->getType());
    }

    public function testGetMoney()
    {
        $this->assertEquals($this->money, $this->cashInOp->getMoney());
    }

}
