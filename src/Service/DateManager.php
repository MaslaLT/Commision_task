<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use DateTime;

class DateManager
{
    /**
     * Compares if two given dates are of same week.
     */
    public function sameWeek(DateTime $lastWeek, DateTime $date): bool
    {
        $diff = $lastWeek->diff($date)->days;

        if ($lastWeek->format('W') === $date->format('W') && $diff <= 7) {
            return true;
        } else {
            return false;
        }
    }
}
