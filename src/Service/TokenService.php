<?php


namespace App\Service;

use DateInterval;
use App\Entity\User;
use App\Entity\Token;
use DateTimeImmutable;
use App\Repository\TokenRepository;
use App\Exception\TokenInvalidException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class TokenService
{
    const REGISTRATION_TOKEN = "registration_token";
    const PASSWORD_RECOVERY_TOKEN = "password_recovery_token";

    private EntityManagerInterface $entityManager;
    private TokenRepository $tokenRepository;

    public function __construct(EntityManagerInterface $entityManager, TokenRepository $tokenRepository)
    {
        $this->entityManager = $entityManager;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Cette méthode retourne null si $token ne contien aucune valeur
     *
     * @param string $token
     * @return void
     */
    public function tokenIsNull(?string $token): bool
    {
        return empty($token);
    }

    /**
     * Cette méthode vérifie si le token contient 64 caractères
     *
     * @param [type] $token Le token à vérifier
     * @return boolean La valeur retouné
     */
    public function isTokenValid(?string $token): bool
    {
        $result = false;
        if (preg_match('/^[a-zA-Z0-9]{64}$/', $token)) {
            $result = true;
        }
        return $result;
    }

    /**
     * Cette méthode génère le token code et le retourne sous forme d'une chaine de caractère.
     *
     * @return string
     */
    public function generateTokenCode(): string
    {
        $generatedToken = bin2hex(openssl_random_pseudo_bytes(32));

        return $generatedToken;
    }

    /**
     * Ce méhtode permet la création de la date d'expiration du token
     *
     * @param string $hours (24 hours) pour 1 jour
     * @return DateTimeImmutable
     */
    public function tokenExpiryTime(string $hours): DateTimeImmutable
    {
        $today = new DateTimeImmutable('now');

        // Ce code permet de formatter le string correctement
        $expireDate = $today->add(DateInterval::createFromDateString($hours));

        return $expireDate;
    }

    /**
     * Cette méthode vérifie si la date du token est encore valide
     *
     * @param \DateTimeImmutable $dateToCheck
     * @return boolean
     */
    public function isTokenAvailable(\DateTimeImmutable $dateToCheck): bool
    {
        $today = new DateTimeImmutable('now');
        $difference = $today->diff($dateToCheck);

        if (($difference->days > 0 || $difference->h > 0 || $difference->i > 0 || $difference->s > 0) && $today > $dateToCheck) {
            return false;
        }
        return true;
    }

    /**
     * Cette méthode Crée l'objet token avec toutes les données
     *
     * @param User $user
     * @param [type] $tokenType
     * @return Token
     */
    public function createToken(User $user, string $tokenType): Token
    {
        $token = new Token();
        $token->setUserToken($user);
        if ($tokenType === "registration") {
            $token->setType(self::REGISTRATION_TOKEN);
        } elseif ($tokenType === "recovery") {
            $token->setType(self::PASSWORD_RECOVERY_TOKEN);
        }
        $token->setToken($this->generateTokenCode());
        $token->setExpireDate($this->tokenExpiryTime("24 hours"));

        return $token;
    }

    /**
     * Vérifie si un token est expiré en comparant la date d'expiration avec la date/heure actuelle.
     *
     * @param \DateTimeInterface $expireDate La date d'expiration du token.
     * @return bool Retourne true si le token est expiré, false sinon.
     */
    private function isTokenExpired(\DateTimeInterface $expireDate): bool
    {
        $now = new \DateTimeImmutable('now', $expireDate->getTimezone());
        return $expireDate <= $now;
    }

    /**
     * Cette méthode vérifie l'adresse mail de l'utilisateur, et met à jour la table utilisateur 
     * pour marquer que le compte utilisateur est vérifié
     *
     * @param string $tokenFromUrl
     * @return boolean
     */
    public function verifyEmail(string $tokenFromUrl): bool
    {
        $this->validateToken($tokenFromUrl);

        $token = $this->tokenRepository->findOneBy(['token' => $tokenFromUrl]);
        $this->ensureTokenIsValid($token);

        $user = $token->getUserToken();
        $this->ensureUserIsValid($user);

        if ($token->getType() === self::REGISTRATION_TOKEN) {
            $this->markUserEmailAsVerified($user, $token);
            return true;
        }
        return false;
    }

    /**
     * Cette méthode lève les exception si le token est vide,
     * ou si le token n'est pas valide
     *
     * @param string $tokenFromUrl
     * @return void
     */
    private function validateToken(string $tokenFromUrl): void
    {
        if ($this->tokenIsNull($tokenFromUrl)) {
            throw TokenInvalidException::emptyToken();
        }

        if (!$this->isTokenValid($tokenFromUrl)) {
            throw TokenInvalidException::invalidFormat();
        }
    }


    /**
     * Cette méthode lève une exception si le token n'existe pas
     *
     * @param Token $token
     * @return void
     */
    private function ensureTokenIsValid(Token $token): void
    {
        if (!$token || $this->isTokenExpired($token->getExpireDate())) {
            throw TokenInvalidException::expired($token->getExpireDate());
        }
    }

    /**
     * Cette méthode lève une exception si le token n'est pas lié un utilisateur.
     * elle s'tuilise avec la méthode verifyEmail().
     *
     * @param User|null $user
     * @return void
     */
    private function ensureUserIsValid(?User $user): void
    {
        if (!$user) {
            throw TokenInvalidException::linkedToNoUser();
        }
    }

    /**
     * Cette méthode marque le compte utilisateur comme vérifier,
     * Elle est utilisé par la méthode VerifyEmail()
     *
     * @param User $user
     * @param Token $token
     * @return void
     */
    private function markUserEmailAsVerified(User $user, Token $token): void
    {
        $user->setEmailVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->remove($token);
        $this->entityManager->flush();
    }
}
