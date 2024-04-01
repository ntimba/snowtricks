<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_base')]
    public function homeBase(): Response
    {

        // Pour proteger la page user
        // $this->denyAccessUnlessGranted('ROLE_USER');
        // $this->isGranted('ROLE_USER'); à utiliser avec if et else
        // #[IsGranted('ROLE_USER')]

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
