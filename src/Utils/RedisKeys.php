<?php

namespace App\Utils;

class RedisKeys
{
    public static function getResetPasswordVKey(string|int $userId): string
    {
        return 'user:' . $userId . ':vKey';
    }

    public static function getLoginFailedAttempts(string|int $userId): string
    {
        return 'user:' . $userId . ':login:failed_attempts';
    }

    public static function getLoginLocked(string|int $userId): string
    {
        return 'user:' . $userId . ':login:locked';
    }

    public static function getLoginLockedTimes(string|int $userId): string
    {
        return 'user:' . $userId . ':login:locked_times';
    }

    public static function getSessionId(string|int $userId): string
    {
        return 'user:' . $userId . ':login:session';
    }

    public static function getAutoLoginToken(string|int $userId): string
    {
        return 'user:' . $userId . ':login:auto_login_token';
    }

    public static function getLastLoginKey(string|int $userId): string
    {
        return 'user:' . $userId . ':login:last_login';
    }
}