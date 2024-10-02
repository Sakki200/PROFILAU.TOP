<?php

namespace App\Controller;

use App\Repository\CoverLetterRepository;
use App\Repository\JobOfferRepository;
use App\Repository\LinkedInMessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function dashboard(JobOfferRepository $jobs, CoverLetterRepository $letters, LinkedInMessageRepository $msg): Response
    {
        $jobs->findBy(['app_user' => $this->getUser()]);
        dd($jobs->getJobOffer());
        $letters->findBy(['app_user' => $this->getUser()]);
        $msg->findBy(['app_user' => $this->getUser()]);


        return $this->render('dashboard/index.html.twig', ['jobs' => $jobs, 'letters' => $letters, 'messages' => $msg]);
    }
}
