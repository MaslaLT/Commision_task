<?php

namespace Masel\CommissionTask\Tests\Transaction;

use DateTime;
use Masel\CommissionTask\Currency\IsoCurrency;
use Masel\CommissionTask\Money\Money;
use Masel\CommissionTask\Operation\Operation;
use Masel\CommissionTask\Transaction\Transaction;
use Masel\CommissionTask\User\User;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    private $transactionCashOut;
    private $transactionCashIn;
    private $date;
    private $currency;
    private $money;
    private $user;
    private $operation;

    public function setUp()
    {
        $this->date = new DateTime('1999-01-05');
        $userNatural = new User(5, 'natural');
        $userLegal = new User(6, 'legal');
        $this->user = $userLegal;
        $this->currency = new IsoCurrency();
        $this->currency->setDecimals(3);
        $this->currency->setCode('tester');
        $this->money = new Money($this->currency, 55);
        $operationCashIn = new Operation('cash_in', $this->money);
        $operationCashOut = new Operation('cash_out', $this->money);
        $this->operation = $operationCashIn;
        $this->transactionCashOut = new Transaction($this->date, $userNatural, $operationCashOut);
        $this->transactionCashIn = new Transaction($this->date, $userLegal, $operationCashIn);
    }

    public function testOperationIsCashOut()
    {
        $this->assertEquals(true, $this->transactionCashOut->operationIsCashOut());
        $this->assertEquals(false, $this->transactionCashIn->operationIsCashOut());
    }

    public function testOperationIsCashIn()
    {
        $this->assertEquals(false, $this->transactionCashOut->operationIsCashIn());
        $this->assertEquals(true, $this->transactionCashIn->operationIsCashIn());
    }

    public function testUserIsLegal()
    {
        $this->assertEquals(false, $this->transactionCashOut->userIsLegal());
        $this->assertEquals(true, $this->transactionCashIn->userIsLegal());
    }

    public function testUserIsNatural()
    {
        $this->assertEquals(true, $this->transactionCashOut->userIsNatural());
        $this->assertEquals(false, $this->transactionCashIn->userIsNatural());
    }

    public function testOperationCurrencyDecimals()
    {
        $this->assertEquals(3, $this->transactionCashIn->operationCurrencyDecimals());
    }

    public function testDate()
    {
        $this->assertEquals($this->date, $this->transactionCashIn->date());
    }

    public function testOperationCurrency()
    {
        $this->assertEquals($this->currency, $this->transactionCashIn->operationCurrency());
    }

    public function testOperationMoney()
    {
        $this->assertEquals($this->money, $this->transactionCashIn->operationMoney());
    }

    public function testUser()
    {
        $this->assertEquals($this->user, $this->transactionCashIn->user());
    }

    public function testOperation()
    {
        $this->assertEquals($this->operation, $this->transactionCashIn->operation());
    }

    public function testOperationMoneyAmount()
    {
        $this->assertEquals($this->money->getAmount(), $this->transactionCashOut->operationMoneyAmount());
    }

}
