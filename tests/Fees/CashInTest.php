<?php

namespace Masel\CommissionTask\Tests\Fees;

use Masel\CommissionTask\Fees\CashIn;
use PHPUnit\Framework\TestCase;

class CashInTest extends TestCase
{
    /**
     * @var CashIn
     */
    private $cashIn;

    public function setUp()
    {
        $this->cashIn = new CashIn();
    }

    public function testSetSingleOperationFee()
    {
        $this->cashIn->setSingleOperationFee(0.055);
        $this->assertEquals(0.055, $this->cashIn->getSingleOperationFee());
    }

    public function testGetSingleOperationFee()
    {
        $this->cashIn->setSingleOperationFee(0.055);
        $this->assertEquals(0.055, $this->cashIn->getSingleOperationFee());
    }

    public function test__construct()
    {
        $this->assertEquals(0.0003, $this->cashIn->getSingleOperationFee());
    }
}
