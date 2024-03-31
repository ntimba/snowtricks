<?php

namespace App\Exception;

use Exception;

class AccountNotVerifiedException extends \Exception
{
    public function __construct(string $message = "Compte non vérifié", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
