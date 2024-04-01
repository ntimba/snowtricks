<?php

namespace App\Exception;

use \Exception;


class TokenInvalidException extends Exception
{
    public static function emptyToken(): self
    {
        return new self("Le token est vide.");
    }

    public static function invalidFormat(): self
    {
        return new self("Le format du token est invalide.");
    }

    public static function notFound(): self
    {
        return new self("Le token fourni n'existe pas ou est invalide.");
    }

    public static function linkedToNoUser(): self
    {
        return new self("Ce token n'est lié à aucun utilisateur.");
    }

    public static function expired(\DateTimeInterface $expireDate): self
    {
        return new self(sprintf("Le token a expiré le %s.", $expireDate->format('Y-m-d H:i:s')));
    }
}
