<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', method: ['GET', 'POST'])]
    public function login(): Response
    {
        return $this->render('security/login.html.twig', []);
    }
    #[Route('/logout', name: 'app_logout', method: ['GET'])]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
