<?php

namespace Masel\CommissionTask\Tests\Transaction;

use DateTime;
use Masel\CommissionTask\Currency\IsoCurrency;
use Masel\CommissionTask\Money\Money;
use Masel\CommissionTask\Operation\Operation;
use Masel\CommissionTask\Resources\FixedExchangeRatesData;
use Masel\CommissionTask\Service\DateManager;
use Masel\CommissionTask\Service\FixedExchangeRateProvider;
use Masel\CommissionTask\Service\MoneyExchanger;
use Masel\CommissionTask\Transaction\ThisWeekTransactions;
use Masel\CommissionTask\Transaction\Transaction;
use Masel\CommissionTask\User\User;
use PHPUnit\Framework\TestCase;

class ThisWeekTransactionsTest extends TestCase
{

    /**
     * @var ThisWeekTransactions
     */
    private $thisWeekTransactions;

    private $transaction;

    private $user;

    private $currency;

    public function setUp()
    {
        $this->user = new User(9, 'natural');
        $date = new DateTime('2000-05-15');
        $this->currency = new IsoCurrency();
        $money = new Money($this->currency, 525);
        $operation = new Operation('cash_out', $money);
        $this->transaction = new Transaction($date, $this->user, $operation);
        $dateManager = new DateManager();
        $exchangeRateProvider = new FixedExchangeRateProvider(FixedExchangeRatesData::getRates());
        $moneyExchanger = new MoneyExchanger($exchangeRateProvider);
        $this->thisWeekTransactions = new ThisWeekTransactions($dateManager, $moneyExchanger);
    }

    public function testAddTransaction()
    {
        $this->thisWeekTransactions->addTransaction($this->transaction);
        $transactionArray = $this->thisWeekTransactions->thisWeekTransactions();

        $this->assertEquals($transactionArray[0], $this->transaction);
    }

    public function testUsersCashOuts()
    {
        $this->thisWeekTransactions->addTransaction($this->transaction);
        $this->thisWeekTransactions->addTransaction($this->transaction);

        $this->assertEquals(2, $this->thisWeekTransactions->usersCashOuts($this->user));
    }

    public function testUsersCashOutSum()
    {
        $this->thisWeekTransactions->addTransaction($this->transaction);
        $this->thisWeekTransactions->addTransaction($this->transaction);

        $this->assertEquals(1050, $this->thisWeekTransactions->usersCashOutSum($this->user, $this->currency));
    }

}
