<?php

namespace Masel\CommissionTask\Tests\Service;

use Masel\CommissionTask\Service\DateManager;
use PHPUnit\Framework\TestCase;

class DateManagerTest extends TestCase
{
    /**
     * @var DateManager
     */
    protected $dateManager;

    public function setUp()
    {
        $this->dateManager = new DateManager();
    }

    public function testWeekFromDate()
    {
        $weekFromDate = $this->dateManager->weekFromDate('1999-01-11');
        $this->assertEquals('02', $weekFromDate);
    }

    public function testSameWeek()
    {
        $weekFromDate1 = '1999-01-09';
        $weekFromDate2 = '1999-01-10';

        $comparisonResult = $this->dateManager->sameWeek($weekFromDate1, $weekFromDate2);
        $this->assertEquals(true, $comparisonResult);
    }

    public function testSameWeekReturnsFalse()
    {
        $weekFromDate1 = '1999-01-09';
        $weekFromDate2 = '1999-02-11';

        $comparisonResult = $this->dateManager->sameWeek($weekFromDate1, $weekFromDate2);
        $this->assertEquals(false, $comparisonResult);
    }

}
