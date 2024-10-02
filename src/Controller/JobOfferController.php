<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(['ROLE_USER'])]
class JobOfferController extends AbstractController
{
    #[Route('/job-offers', name: 'app_job_offer_all', method: 'GET')]
    public function all(): Response
    {
        return $this->render('job_offer/list.html.twig', []);
    }
    #[Route('/job-offers/{id}', name: 'app_job_offer_show', method: 'GET')]
    public function show(): Response
    {
        return $this->render('job_offer/list.html.twig', []);
    }
    #[Route('/job-offers/{id}/edit', name: 'app_job_offer_edit', method: ['GET', 'POST'])]
    public function edit(): Response
    {
        return $this->render('job_offer/edit.html.twig', []);
    }
    #[Route('/job-offers/{id}/delete', name: 'app_job_offer_delete', method: ['POST'])]
    public function delete(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }
    #[Route('/job-offers/new', name: 'app_job_offer_new', method: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->render('job_offer/new.html.twig', []);
    }
}
