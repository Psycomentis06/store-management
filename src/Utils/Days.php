<?php

namespace App\Utils;

class Days
{
    private static array $dayNames = array(
        'Sunday' => 0,
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6,
    );

    public static function get(string $name)
    {
        return self::$dayNames[$name];
    }
}