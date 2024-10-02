<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\JobOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class JobOfferController extends AbstractController
{
    #[Route('/job-offers', name: 'app_job_offer_all', methods: 'GET')]
    public function all(): Response
    {
        return $this->render('job_offer/list.html.twig', []);
    }
    #[Route('/job-offers/{id}', name: 'app_job_offer_show', methods: 'GET')]
    public function show(): Response
    {        
        return $this->render('job_offer/list.html.twig', []);
    }
    #[Route('/job-offers/{id}/edit', name: 'app_job_offer_edit', methods: ['GET', 'POST'])]
    public function edit(): Response
    {
        return $this->render('job_offer/edit.html.twig', []);
    }
    #[Route('/job-offers/{id}/delete', name: 'app_job_offer_delete', methods: ['POST'])]
    public function delete(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }
    #[Route('/job-offer/new', name: 'app_job_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $job = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setAppUser($this->getUser());
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('/job-offers/' . $job->getId());
        }

        return $this->render('job_offer/new.html.twig', ['formJobOffer' => $form]);
    }
}
