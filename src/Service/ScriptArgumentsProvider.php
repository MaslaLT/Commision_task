<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

class ScriptArgumentsProvider
{
    /**
     * @var int
     */
    private $argumentsExpected;

    public function __construct(int $argumentsExpected)
    {
        $this->argumentsExpected = $argumentsExpected;
    }

    /**
     * @throws \Exception
     */
    public function getArgument(string $elementId): string
    {
        if ($this->validateArgumentsAmount()) {
            return $_SERVER['argv'][$elementId];
        }
        throw new \Exception('Wrong script arguments amount passed. Need to pass '.$this->argumentsExpected);
    }

    private function validateArgumentsAmount(): bool
    {
        if (count($_SERVER['argv']) === $this->argumentsExpected) {
            return true;
        }

        return false;
    }
}
