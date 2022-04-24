<?php

namespace App\Exception;

class AuthenticationException extends \Symfony\Component\Security\Core\Exception\AuthenticationException
{
    public const SINGLE_SESSION = 3;
    public const FAILED_ATTEMPTS = 2;
    public const USER_NOTFOUND = 1;

    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}