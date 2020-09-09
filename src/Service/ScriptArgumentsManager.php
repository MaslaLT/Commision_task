<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use Masel\CommissionTask\Exceptions\WrongArgumentNumberException;

class ScriptArgumentsManager
{
    /**
     * PHP super global $argv returns array of passed arguments to script.
     *
     * @var array
     */
    protected $argv;

    /**
     * Holds number of arguments, needs to be passed for a script.
     * Default value = 2.
     *
     * @var int
     */
    protected $scriptArgumentsAmount;

    public function __construct($argv, $scriptArgumentsAmount = 2)
    {
        $this->argv = $argv;
        $this->scriptArgumentsAmount = $scriptArgumentsAmount;

        try {
            $this->validateArgumentsAmount();
        } catch (WrongArgumentNumberException $e) {
            echo $e->getMessage();
        }
    }

    public function setArgumentsAmount(int $amount)
    {
        $this->scriptArgumentsAmount = $amount;
    }

    public function getArgumentsAmount(): int
    {
        return $this->scriptArgumentsAmount;
    }

    public function getArgument(string $elementId): string
    {
        return $this->argv[$elementId];
    }

    protected function validateArgumentsAmount()
    {
        $argumentsPassed = count($this->argv);
        $argumentsNeeded = $this->scriptArgumentsAmount;

        if ($argumentsPassed === $argumentsNeeded) {
            return $this->argv;
        } else {
            $message = 'Error. Wrong amount of arguments passed. '.'You passed '.$argumentsPassed.' instead of '.$argumentsNeeded.PHP_EOL;
            throw new \Exception($message);
        }
    }
}
