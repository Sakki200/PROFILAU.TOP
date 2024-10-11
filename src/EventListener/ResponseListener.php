<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        // Ajouter HSTS seulement pour les connexions HTTPS
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        // Ajouter le Content Security Policy (CSP)
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' https://ga.jspm.io; style-src 'self' https://fonts.googleapis.com; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; img-src 'self'; frame-ancestors 'self'; form-action 'self';");

        // Ajouter l'en-tête X-Frame-Options
        $response->headers->set('X-Frame-Options', 'DENY');

        // Ajouter l'en-tête X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');
    }
}
