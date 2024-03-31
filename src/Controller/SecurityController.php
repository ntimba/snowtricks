<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Token;
use App\Form\UserType;
use App\Repository\TokenRepository;
use App\Service\EmailService;
use App\Service\EmailVerificationService;
use App\Service\SecurityService;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    private $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

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
            } catch (Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('home_base');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $userForm->createView()
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login()
    {
    }

    #[Route('/verify/email', name: 'verify_email')]
    public function verifyEmail(Request $request, TokenService $tokenService, TokenRepository $tokenRepository, UrlGeneratorInterface $router)
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
