<?php

declare(strict_types=1);

namespace Masel\CommissionTask\User;

class User
{
    const LEGAL = 'legal';

    const NATURAL = 'natural';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    public function __construct(int $id, string $type)
    {
        if (0 > $id) {
            throw new \Exception('Id cant be negative. %s given', $id);
        }

        $this->id = $id;
        $this->type = $type;
    }

    public function isNatural(): bool
    {
        if ($this::NATURAL === $this->type) {
            return true;
        } else {
            return false;
        }
    }

    public function isLegal(): bool
    {
        if ($this::LEGAL === $this->type) {
            return true;
        } else {
            return false;
        }
    }

    public function id(): int
    {
        return $this->id;
    }
}
