<?php

namespace App\Exception;

use Exception;

class UserNotFoundException extends \Exception
{
    public function __construct(string $message = "Utilisateur non trouvé", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
