<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route(
        path: '/sso/connect/{service}',
        name: 'app_sso_connect',
        methods: ['GET'])]
    public function connect(string $service): null|RedirectResponse
    {
        if ($service === 'strava') {
            $urlToRedirect = 'https://www.strava.com/oauth/authorize?client_id=131358&response_type=code&redirect_uri=https://localhost/exchange_token&approval_prompt=force&scope=read';
            return $this->redirect($urlToRedirect);
        }

        return $this->redirectToRoute('app_home');
    }
}
