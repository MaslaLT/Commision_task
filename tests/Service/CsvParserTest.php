<?php

namespace Masel\CommissionTask\Tests\Service;

use Masel\CommissionTask\Exceptions\WrongFileTypePassed;
use Masel\CommissionTask\Service\CsvParser;
use Masel\CommissionTask\Service\ScriptArgumentsManager;
use PHPUnit\Framework\TestCase;

class CsvParserTest extends TestCase
{
    public function testGetParsedCsvRows()
    {
        $argv = [
            '0' => __DIR__.'/../input.csv',
        ];
        $scriptArgumentManager = new ScriptArgumentsManager($argv, 1);
        $csvParser = new CsvParser($scriptArgumentManager, '0');

        $getRows = $csvParser->getParsedCsvRows();
        $expectation = [
            '0' => ['2014-12-31', '4', 'natural', 'cash_out', '1200.00', 'EUR'],
        ];
        $this->assertEquals($expectation, $getRows);
    }

    public function testCsvParserTakesOnlyCsvFile()
    {
        $this->expectExceptionMessage('Wrong file type passed. csv Expected');

        $argv = [
            '0' => __DIR__.'/../input.txt',
        ];
        $scriptArgumentManager = new ScriptArgumentsManager($argv, 1);
        new CsvParser($scriptArgumentManager, '0');
    }

}
