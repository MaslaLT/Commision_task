<?php

namespace Masel\CommissionTask\Tests\Service;

use Masel\CommissionTask\Service\ScriptArgumentsManager;
use PHPUnit\Framework\TestCase;

class ScriptArgumentsManagerTest extends TestCase
{
    protected $scriptArgumentManager;

    public function setUp()
    {
        $argv = ['script.php', 'test.csv', 'test2.csv'];
        $this->scriptArgumentManager = new ScriptArgumentsManager($argv, 3);
    }

    public function testSetArgumentsAmount()
    {
        $this->scriptArgumentManager->setArgumentsAmount(4);
        $this->assertEquals(4, $this->scriptArgumentManager->getArgumentsAmount());
    }

    public function testGetArgument()
    {
        $argument1 = $this->scriptArgumentManager->getArgument('1');
        $argument2 = $this->scriptArgumentManager->getArgument('2');

        $this->assertEquals('test.csv', $argument1);
        $this->assertEquals('test2.csv', $argument2);
    }

    public function test__construct()
    {
        $argv = ['script.php', 'test.csv'];
        $scriptArgumentManager = new ScriptArgumentsManager($argv, 2);

        $this->assertEquals('script.php', $scriptArgumentManager->getArgument('0'));
        $this->assertEquals('test.csv', $scriptArgumentManager->getArgument('1'));
    }
}
