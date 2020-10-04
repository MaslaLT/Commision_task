<?php

namespace Masel\CommissionTask\Tests\Service;

use Masel\CommissionTask\Service\ScriptArgumentsProvider;
use PHPUnit\Framework\TestCase;

class ScriptArgumentsProviderTest extends TestCase
{
    private $scriptArgumentsProvider;

    public function setUp()
    {
        $argv = ['script.php', 'test.csv', 'test2.csv'];
        $_SERVER['argv'] = $argv;
        $this->scriptArgumentsProvider = new ScriptArgumentsProvider('3');
    }

    public function testGetArgument()
    {
        $this->assertEquals('script.php', $this->scriptArgumentsProvider->getArgument(0));
        $this->assertEquals('test.csv', $this->scriptArgumentsProvider->getArgument(1));
        $this->assertEquals('test2.csv', $this->scriptArgumentsProvider->getArgument(2));
    }

    public function testGetArgumentThrowsException()
    {
        $this->expectException(\Exception::class);
        $_SERVER['argv'] = ['script.php', 'test.csv'];
        $this->assertEquals('test2.csv', $this->scriptArgumentsProvider->getArgument(2));
    }

}
