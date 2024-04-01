<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserEmailVerifiedChecker implements UserCheckerInterface
{
    /**
     * Cette méthode s'assure que le compte utilisateur soit vérifier avant de l'authentifier
     *
     * @param UserInterface $user
     * @return void
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isEmailVerified()) {
            throw new CustomUserMessageAccountStatusException('Votre email n\'a pas été vérifié.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // D'autres vérifications après l'authentification si nécessaire.
    }
}
