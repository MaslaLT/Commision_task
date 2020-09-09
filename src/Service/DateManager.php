<?php

declare(strict_types=1);

namespace Masel\CommissionTask\Service;

use DateTime;

class DateManager
{
    /**
     * Converts date string to week of the year.
     */
    public function weekFromDate(string $date): string
    {
        try {
            $dateTime = new DateTime($date);
        } catch (\Exception $e) {
            echo $e;
            die('Wrong date format');
        }

        return $dateTime->format('W');
    }

    /**
     * Compares if two given dates are of same week.
     */
    public function sameWeek(string $lastWeek, string $date): bool
    {
        $diff = strtotime($date) - strtotime($lastWeek);
        $diffInDays = $diff / 60 / 60 / 24;

        if ($this->weekFromDate($date) === $this->weekFromDate($lastWeek) && $diffInDays <= 7) {
            return true;
        } else {
            return false;
        }
    }
}
