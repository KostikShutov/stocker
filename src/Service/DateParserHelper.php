<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;

class DateParserHelper
{
    public static function getDateFromFormat(string $date, string $format = 'd.m.Y'): ?DateTime
    {
        $date = date_create_from_format($format, $date);

        return $date instanceof DateTime ? $date->setTime(0, 0) : null;
    }
}
