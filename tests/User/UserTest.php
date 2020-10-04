<?php

namespace Masel\CommissionTask\Tests\User;

use Masel\CommissionTask\User\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $legalUser;
    private $naturalUser;

    public function setUp()
    {
        $this->legalUser = new User(5, User::LEGAL);
        $this->naturalUser = new User(6, User::NATURAL);
    }

    public function test__constructorThrowsException()
    {
        $this->expectException(\Exception::class);
        new User(-5, User::LEGAL);
    }

    public function testId()
    {
        $this->assertEquals(5, $this->legalUser->id());
    }

    public function testIsNatural()
    {
        $this->assertEquals(false, $this->legalUser->isNatural());
        $this->assertEquals(true, $this->naturalUser->isNatural());
    }

    public function testIsLegal()
    {
        $this->assertEquals(true, $this->legalUser->isLegal());
        $this->assertEquals(false, $this->naturalUser->isLegal());
    }

}
