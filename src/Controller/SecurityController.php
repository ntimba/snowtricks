<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\Token;
use App\Form\UserType;
use App\Service\EmailService;
use App\Service\TokenService;
use App\Service\SecurityService;
use App\Repository\TokenRepository;
use App\Exception\TokenInvalidException;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\EmailVerificationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SecurityController extends AbstractController
{
    private $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Cette méthode crée un nouvel utilisateur.
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/registration', name: 'registration')]
    public function register(Request $request): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            try {
                $this->securityService->registration($user);
                $this->addFlash("success", "Compte créé avec succès");
            } catch (TokenInvalidException $e) {
                $this->addFlash('error', $e->getMessage());
            } catch (Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('home_base');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $userForm->createView()
        ]);
    }

    /**
     * Cette méthode connecte l'utilisateur
     *
     * @return void
     */
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $errors = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $errors,
            'username' => $username
        ]);
    }

    /**
     * Cette méthode deconnecte l'utilisateur
     *
     * @return void
     */
    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
    }

    /**
     * Cette méthode vérifie le compte de l'utilisateur
     *
     * @param Request $request
     * @param TokenService $tokenService
     * @return Response
     */
    #[Route('/verify/email', name: 'verify_email')]
    public function verifyEmail(Request $request, TokenService $tokenService): Response
    {
        $tokenFromUrl = $request->query->get('token');

        try {
            if ($tokenService->verifyEmail($tokenFromUrl)) {
                $this->addFlash('success', 'Votre adresse e-mail a été vérifiée avec succès.');
            } else {
                $this->addFlash('error', 'Le token est invalide ou expiré.');
            }
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('home_base');
    }
}
