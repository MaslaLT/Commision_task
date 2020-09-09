<?php

namespace Masel\CommissionTask\Tests\Fees;

use Masel\CommissionTask\Fees\CashOut;
use PHPUnit\Framework\TestCase;

class CashOutTest extends TestCase
{

    /**
     * @var CashOut
     */
    protected $cashOut;

    public function setUp()
    {
        $this->cashOut = new CashOut();
    }

    public function testSetFreeCashOutPerWeek()
    {
        $this->cashOut->setFreeCashOutPerWeek(1950);
        $this->assertEquals(1950, $this->cashOut->getFreeCashOutPerWeek());
    }

    public function testSetFreeOperationsPerWeek()
    {
        $this->cashOut->setFreeOperationsPerWeek(5);
        $this->assertEquals(5, $this->cashOut->getFreeOperationsPerWeek());
    }

    public function testSetSingleOperationFee()
    {
        $this->cashOut->setSingleOperationFee(0.5);
        $this->assertEquals(0.5, $this->cashOut->getSingleOperationFee());
    }

    public function testGetFreeOperationsPerWeek()
    {
        $this->cashOut->setFreeOperationsPerWeek(9);
        $this->assertEquals(9, $this->cashOut->getFreeOperationsPerWeek());
    }

    public function testGetSingleOperationFee()
    {
        $this->cashOut->setSingleOperationFee(0.09);
        $this->assertEquals(0.09, $this->cashOut->getSingleOperationFee());
    }

    public function testGetFreeCashOutPerWeek()
    {
        $this->cashOut->setFreeCashOutPerWeek(999);
        $this->assertEquals(999, $this->cashOut->getFreeCashOutPerWeek());
    }

    public function testGetCashOutFees()
    {
        $this->cashOut->setSingleOperationFee(0.5);
        $this->cashOut->setFreeOperationsPerWeek(500);
        $this->cashOut->setFreeCashOutPerWeek(10);

        $allFeesArray = $this->cashOut->getCashOutFees();
        $this->assertArrayHasKey('singleOperation', $allFeesArray);
        $this->assertArrayHasKey('freeOperationsPerWeek', $allFeesArray);
        $this->assertArrayHasKey('freeCashOutPerWeek', $allFeesArray);
        $this->assertEquals(0.5, $this->cashOut->getSingleOperationFee());
        $this->assertEquals(500, $this->cashOut->getFreeOperationsPerWeek());
        $this->assertEquals(10, $this->cashOut->getFreeCashOutPerWeek());
    }

    public function test__construct()
    {
        $this->assertEquals(0.003, $this->cashOut->getSingleOperationFee());
        $this->assertEquals(3, $this->cashOut->getFreeOperationsPerWeek());
        $this->assertEquals(1000, $this->cashOut->getFreeCashOutPerWeek());
    }

}
