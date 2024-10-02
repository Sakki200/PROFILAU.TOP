<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(['ROLE_USER'])]
class LinkedInMessageController extends AbstractController
{
    #[Route('/linkedin-message/generate', name: 'app_linkedin_generate', method: 'POST')]
    public function generate(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }
    #[Route('/linkedin-message/{id}', name: 'app_linkedin_show', method: 'GET')]
    public function show(): Response
    {
        return $this->render('linkedin_message/show.html.twig', []);
    }
}
