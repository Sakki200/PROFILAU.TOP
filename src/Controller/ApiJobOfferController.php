<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ApiJobOfferController extends AbstractController
{
    #[Route('/api/job-offers/update-status', name: 'app_api_job_update', methods: 'POST')]
    public function update(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }
}
