<?php

namespace Masel\CommissionTask\Tests\Fees;

use Masel\CommissionTask\Currency\IsoCurrency;
use Masel\CommissionTask\Fees\CashOut;
use Masel\CommissionTask\Money\Money;
use PHPUnit\Framework\TestCase;

class CashOutTest extends TestCase
{
    private $cashOut;
    private $freeCashOutPerWeek;
    private $legalCashOutMin;

    public function setUp()
    {
        $isoCurrency = new IsoCurrency();
        $this->legalCashOutMin = new Money($isoCurrency, 99);
        $this->freeCashOutPerWeek = new Money($isoCurrency, 250);
        $this->cashOut = new CashOut(0.5, 5, $this->freeCashOutPerWeek, $this->legalCashOutMin);
    }

    public function testGetFreeCashOutPerWeek()
    {
        $this->assertEquals($this->freeCashOutPerWeek, $this->cashOut->getFreeCashOutPerWeek());
    }

    public function testGetSingleOperationFee()
    {
        $this->assertEquals(0.5, $this->cashOut->getSingleOperationFee());
    }

    public function testGetFreeOperationsPerWeek()
    {
        $this->assertEquals(5, $this->cashOut->getFreeOperationsPerWeek());
    }

    public function testGetLegalCashOutMin()
    {
        $this->assertEquals($this->legalCashOutMin, $this->cashOut->getLegalCashOutMin());
    }

}
