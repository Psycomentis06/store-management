<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ResetPasswordException extends AuthenticationException
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}