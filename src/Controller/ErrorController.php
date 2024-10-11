<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error')]
    public function error(Throwable $exception): Response
    {
        $statusCode = ($exception instanceof HttpExceptionInterface) ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        // Créer une réponse pour l'erreur
        $response = new Response();
        $response->setStatusCode($statusCode);

        // Ajouter des en-têtes de sécurité
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Personnaliser le contenu de l'erreur selon le code d'erreur
        if ($statusCode === 404) {
            $response->setContent('<h1>Page non trouvée (404)</h1>');
        } elseif ($statusCode === 500) {
            $response->setContent('<h1>Erreur interne du serveur (500)</h1>');
        }
        return $response;
    }
}
