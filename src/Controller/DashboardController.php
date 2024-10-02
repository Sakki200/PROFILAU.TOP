<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function dashboard(UserRepository $user): Response
    {
        $user = $user->findOneById($this->getUser()->getId());


        return $this->render('dashboard/index.html.twig', [
            'jobs' => $user->getJobOffers(),
            'letters' => $user->getCoverLetters(),
            'messages' => $user->getLinkedInMessages()
        ]);
    }
}
