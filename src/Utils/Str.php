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

    public static function underscoresToCapital(string $str): string
    {
        $ar = explode('_', $str);
        $res = "";
        foreach ($ar as $item) {
            $res .= ucfirst($item);
        }

        return $res;
    }

    public static function capitalToUnderscore(string $str): string
    {
        $ar = preg_split('/(?=[A-Z])/', $str, -1, PREG_SPLIT_NO_EMPTY);
        return strtolower(join('_', $ar));
    }
}