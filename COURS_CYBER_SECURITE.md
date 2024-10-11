# COURS RAPIDE CYBER-SÉCURITÉ !

## Tester son application sur ZAP !
Lien pour apprendre à bien l'utiliser :
[ZAP Tutorial](https://www.youtube.com/watch?v=7cMTWFDtJbk)

Après nos tests, nous nous retrouvons avec beaucoup de flags de failles de sécurité.
Je vous invite à comprendre ce qu'elles sont avant de commencer à déboguer en demandant à une IA de vous expliquer.

## Tuons ces flags de fails !

### Configuration de l'environnement de production

Dans un premier temps, nous allons dans notre .env pour changer :
```plaintext
APP_ENV=prod
APP_DEBUG=0
```

De la même façon que pour APP_DEBUG=0, allons faire un tour dans config/packages/monolog.yaml :
```yaml
when@prod:
    monolog:
        handlers:
            main:
                level: error (ou info)
```

Le mode debug par défaut n'est pas du tout approprié en production pour la sécurité.

Avec ça, beaucoup de soucis seront réglés car le mode dev permet trop de failles ainsi que le debug affiche les erreurs en détail, ce qui est très mauvais pour la sécurité.

### Gestion des headers de sécurité

Maintenant, attaquons-nous aux problèmes liés aux requêtes des headers.

Dans votre dossier src/, créez un dossier et un fichier comme ceci :
```plaintext
EventListener/ResponseListener.php
```

Ensuite, pour l'activer afin qu'il se lance à chaque requête, ajoutez le code suivant dans config/services.yaml :
```yaml
services:
    App\EventListener\ResponseListener:
        tags:
            - { name: kernel.event_listener, event: kernel.response, method: onKernelResponse }
```

Nous avons donc un event et une méthode nommée onKernelResponse. Ajoutons-les dans notre ResponseListener :

```php
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
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' https://exemple.com; style-src 'self' https://exemple.com; font-src 'self' https://exemple.com; img-src 'self'; frame-ancestors 'self'; form-action 'self';");

        // Ajouter l'en-tête X-Frame-Options
        $response->headers->set('X-Frame-Options', 'DENY');

        // Ajouter l'en-tête X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');
    }
}
```

### Détails des en-têtes ajoutés

**Strict-Transport-Security** :
- max-age=31536000 : Indique que le navigateur doit se rappeler d'utiliser HTTPS pour ce site pendant 1 an (31 536 000 secondes).
- includeSubDomains : Applique également cette règle aux sous-domaines.
- preload : Si tu souhaites inclure ton domaine dans la liste HSTS preload des navigateurs, ajoute cette option et inscris ton domaine sur hstspreload.org.

**Content-Security-Policy** :
- script-src 'self' https://exemple.com; : Autorise de lancer des scripts provenant de notre site ('self') et de https://exemple.com.
- Note : Il est obligatoire d'avoir "frame-ancestors 'self'; form-action 'self';". Faites bien attention à avoir des ; et non des , entre chaque paramètre. De même, faites très attention aux sauts de ligne, il n'aime pas ça, vaut mieux tout mettre sur une seule ligne.

**X-Frame-Options** :
- DENY : Empêche complètement le site d'être intégré dans un iframe.
- SAMEORIGIN : Permet l'intégration dans un iframe seulement si le site est du même domaine (origine).
- ALLOW-FROM uri : Permet l'intégration uniquement depuis un domaine spécifique (peu supporté par les navigateurs modernes).

**X-Content-Type-Options** :
- nosniff : Empêche les navigateurs de deviner ("sniffer") le type de contenu d'une ressource et force le respect du type MIME déclaré par le serveur. Évitant que des fichiers mal étiquetés soient interprétés comme exécutables (par exemple, du JavaScript déguisé en image), réduisant ainsi les risques d'attaques XSS.

### Vérification des headers

Pour vérifier si vos headers fonctionnent :
1. Allez dans votre application sur votre navigateur.
2. Ouvrez les outils de développement (F12).
3. Allez dans l'onglet Réseau.
4. Rafraîchissez la page (F5) s'il n'y a rien.
5. Sélectionnez une requête (ex: 127.0.0.1).
6. Dans le tableau à droite, cherchez "En-têtes de réponse" ou "Response Headers" et vous devriez voir ce que vous avez configuré.

### Débogage des headers

Si ce n'est pas le cas, voici comment déboguer :

1. Pensez à vider votre cache et redémarrer votre serveur :
```bash
symfony console cache:clear
symfony server:start
```

2. Vérifiez que votre ResponseListener est bien fonctionnel :
```bash
symfony console debug:event kernel.response
```

Vous devriez trouver "App\EventListener\ResponseListener::onKernelResponse()" avec une priorité de 0 (prouvant qu'il s'exécute en premier).

3. Si cela ne fonctionne toujours pas, tout se passera dans le fichier ResponseListener.php. Vous avez peut-être mal configuré vos headers.

### Gestion des erreurs 404 et 500

On va créer un contrôleur pour les erreurs src/Controller/ErrorController.php.

Comme avec le fichier juste avant, on va aussi l'appeler dans notre configuration mais cette fois dans config/packages/framework.yaml :
```yaml
framework:
    error_controller: App\Controller\ErrorController::error
```

Dans notre ErrorController.php :
```php
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

        // Ajouter des headers de sécurité
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
```

Ici grâce à $statusCode on peut récupérer le code des erreurs, puis empêcher les problèmes en redirigeant les erreurs 404 et 500

Détail également, j'ai remis certains headers car quand une erreur survient l'application coupe la plupart des processus et donc notre ResponseListener.php ne s'exécute pas jusqu'au bout.

Je vous invite donc à bien vérifier vos headers en allant sur une page en erreur 404 et 500 pour rajouter dans le ErrorController.php les headers qu'il manquait.
Dans mon cas il y avait : X-Frame-Options et X-Content-Type-Options