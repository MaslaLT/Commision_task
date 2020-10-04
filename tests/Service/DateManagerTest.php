<?php

namespace Masel\CommissionTask\Tests\Service;

use DateTime;
use Masel\CommissionTask\Service\DateManager;
use PHPUnit\Framework\TestCase;

class DateManagerTest extends TestCase
{
    private $dateManager;

    public function setUp()
    {
        $this->dateManager = new DateManager();
    }

    /**
     * @param $date1 string
     * @param $date2 string
     * @param $expected bool
     *
     * @dataProvider dataSameWeekTesting
     */
    public function testSameWeek($date1, $date2, $expected)
    {
        $lastWeek = new DateTime($date1);
        $date = new DateTime($date2);
        $this->assertEquals($expected, $this->dateManager->sameWeek($lastWeek, $date));
    }

    public function dataSameWeekTesting(): array
    {
        return [
            'same week' => ['2014-12-31', '2015-01-01', true],
            'same week different year' => ['2014-12-31', '2015-12-31', false],
            'not same week' => ['2014-12-31', '2015-01-07', false],
        ];
    }
}
