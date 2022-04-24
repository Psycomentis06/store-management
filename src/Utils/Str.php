<?php

namespace App\Utils;

class Str
{
    public static function isEmail(string $str): bool
    {
        return preg_match('', $str);
    }

    public static function isUsername(string | int $str): bool
    {
        return preg_match('',$str);
    }
}