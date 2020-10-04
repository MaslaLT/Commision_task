<?php

namespace Masel\CommissionTask\Tests\Service;

use Masel\CommissionTask\Service\CsvParser;
use PHPUnit\Framework\TestCase;

class CsvParserTest extends TestCase
{

    public function testGetParsedCsvRows()
    {
        $csvParser = new CsvParser(__DIR__.'/../input.csv');
        $getRows = $csvParser->getParsedCsvRows();
        $expectation = [
            '0' => ['2014-12-31', '4', 'natural', 'cash_out', '1200.00', 'EUR'],
        ];
        $this->assertEquals($expectation, $getRows);
    }

    public function testGetParsedCsvRowsThrowsException()
    {
        $this->expectException(\Exception::class);
        new CsvParser(__DIR__.'/../input.txt');
    }
}
