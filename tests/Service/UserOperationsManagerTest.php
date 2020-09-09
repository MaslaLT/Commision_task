<?php

namespace Masel\CommissionTask\Tests\Service;

use Masel\CommissionTask\Currency\Currency;
use Masel\CommissionTask\Service\UserOperationsManager;
use PHPUnit\Framework\TestCase;

class UserOperationsManagerTest extends TestCase
{
    private $userOperationsManager;
    private $currency;

    public function setUp()
    {
        $this->currency = new Currency(0.01, 1, 'test');
        $this->userOperationsManager = new UserOperationsManager();
    }

    public function testGetThisWeekOperations()
    {
        $this->userOperationsManager->addThisWeakOperation(
            '1', 2500, $this->currency, '1999-01-08', 'cash_in'
        );

        $expectation[] = [
            'userId' => '1',
            'opAmountEur' => 2500,
            'date' => '1999-01-08',
            'opType' => 'cash_in',
        ];

        $this->assertEquals($expectation, $this->userOperationsManager->getThisWeekOperations());
    }

    /**
     * @param string $userId
     * @param float  $opAmount
     * @param string $date
     * @param string $opType
     * @param array  $expectation
     *
     * @dataProvider dataProviderForAddThisWeakOperation
     */
    public function testAddThisWeakOperation($userId, $opAmount, $date, $opType, $expectation)
    {
        $opCurrency = $this->currency;
        $this->userOperationsManager->addThisWeakOperation($userId, $opAmount, $opCurrency, $date, $opType);

        $this->assertEquals($expectation, $this->userOperationsManager->getThisWeekOperations());
    }

    public function dataProviderForAddThisWeakOperation(): array
    {
        return [
            'Add operation to $thisWeekOperations' => [
                '1', 1000, '1999-01-08', 'cash_in', [
                    [
                    'userId' => '1',
                    'opAmountEur' => 1000,
                    'date' => '1999-01-08',
                    'opType' => 'cash_in',
                    ],
                ],
            ],
        ];
    }

    public function testGetUserOpNumberAndAmountForThisWeak()
    {
        $this->userOperationsManager->addThisWeakOperation(
            '1', 2500, $this->currency, '1999-01-08', 'cash_in'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '2', 2500, $this->currency, '1999-01-08', 'cash_in'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '1', 500, $this->currency, '1999-01-08', 'cash_out'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '2', 500, $this->currency, '1999-01-08', 'cash_out'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '1', 250, $this->currency, '1999-01-08', 'cash_in'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '2', 500, $this->currency, '1999-01-08', 'cash_out'
        );

        $expectation = [
            'cashInOperations' => '1',
            'cashOutOperations' => '2',
            'totalCasOutAmount' => '1000',
        ];

        $this->assertEquals($expectation, $this->userOperationsManager->getUserOpNumberAndAmountForThisWeak('2'));
    }

    public function testSetThisWeekOperationWillResetWeekOperationArray()
    {
        $this->userOperationsManager->addThisWeakOperation(
            '1', 500, $this->currency, '1999-01-08', 'cash_in'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '2', 1000, $this->currency, '1999-01-10', 'cash_in'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '1', 2500, $this->currency, '1999-01-11', 'cash_in'
        );
        $this->userOperationsManager->addThisWeakOperation(
            '2', 500, $this->currency, '1999-01-11', 'cash_out'
        );

        $expectation[] = [
            'userId' => '1',
            'opAmountEur' => 2500,
            'date' => '1999-01-11',
            'opType' => 'cash_in',
        ];

        $expectation[] = [
            'userId' => '2',
            'opAmountEur' => 500,
            'date' => '1999-01-11',
            'opType' => 'cash_out',
        ];

        $this->assertEquals($expectation, $this->userOperationsManager->getThisWeekOperations());
    }
}
