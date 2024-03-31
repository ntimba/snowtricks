<?php

namespace App\Service;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityService
{
    private $passwordHasher;
    private $manager;
    private $tokenService;
    private $router;
    private $emailService;

    public const DEFAULT_PROFILE_IMAGE = '/images/profile.png';
    public const NO_REPLY_EMAIL = "no-reply@snowtricks.com";

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager, TokenService $tokenService, RouterInterface $router, EmailService $emailService)
    {
        $this->passwordHasher = $passwordHasher;
        $this->manager = $manager;
        $this->tokenService = $tokenService;
        $this->router = $router;
        $this->emailService = $emailService;
    }

    /**
     * Cette méthode enregistre l'utilisateur dans la base de données.
     *
     * @param User $user
     * @return void
     */
    public function registration(User $user): void
    {
        $this->hashUserPassword($user);
        $user->setEmailVerified(false);
        $user->setProfileImage(self::DEFAULT_PROFILE_IMAGE);
        $this->manager->persist($user);

        $token = $this->tokenService->createToken($user, "registration");
        $this->manager->persist($token);

        $this->sendConfirmationEmail($user, $token);

        $this->manager->flush();
    }

    /**
     * Cette méthode hash le mot de passe entré par l'utilisateur
     *
     * @param User $user
     * @return void
     */
    private function hashUserPassword(User $user): void
    {
        $plaintextPassword = $user->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
    }

    /**
     * Cette méthode envoi le message de confirmation du création du compte.
     *
     * @param User $user
     * @param [type] $token
     * @return void
     */
    public function sendConfirmationEmail(User $user, Token $token): void
    {
        $confirmationUrl = $this->router->generate('verify_email', ['token' => $token->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
        $this->emailService->sendEmail(
            self::NO_REPLY_EMAIL,
            $user->getEmail(),
            "Confirmation de l'adresse E-mail",
            "emails/reigstration_email.html.twig",
            ['username' => $user->getUsername(), 'siteUrl' => "snowtricks.com", 'confirmationUrl' => $confirmationUrl]
        );
    }
}
