<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(['ROLE_USER'])]
class CoverLetterController extends AbstractController
{
    #[Route('/cover-letter/generate', name: 'app_cover_letter_generate', method: 'POST')]
    public function generate(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }
    #[Route('/cover-letter/{id}', name: 'app_cover_letter_show', method: 'GET')]
    public function show(): Response
    {
        return $this->render('cover_latter/show.html.twig', []);
    }
}
