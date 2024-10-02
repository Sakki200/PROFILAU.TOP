<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        return $this->render('dashboard/index.html.twig', []);
    }
}
