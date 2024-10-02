<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(['ROLE_USER'])]
class ApiJobOfferController extends AbstractController
{
    #[Route('/api/job-offers/update-status', name: 'app_api_job_update', method: 'POST')]
    public function update(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }
}
