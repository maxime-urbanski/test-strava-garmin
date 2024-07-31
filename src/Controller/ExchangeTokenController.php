<?php

namespace App\Controller;

use App\Service\FetchService;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsController]
#[Route('/exchange_token', name: 'app_exchange_token')]
class ExchangeTokenController extends AbstractController
{
    const string STRAVA_URL_LOGIN = 'https://www.strava.com/oauth/token'; # http://www.strava.com/oauth/authorize?client_id=131358&response_type=code&redirect_uri=http://localhost/exchange_token&approval_prompt=force&scope=read

    public function __invoke(
        Request $request,
        FetchService $fetchService
    ): Response
    {
        $stravaCode = $request->get('code');
        $stravaClientId = $this->getParameter('STRAVA_CLIENT_ID');
        $stravaClientSecret = $this->getParameter('STRAVA_CLIENT_SECRET');
        $stravaTokenId = $this->getParameter('STRAVA_TOKEN_ID');

        $stravaUrlReq = self::STRAVA_URL_LOGIN . '?client_id=' . $stravaClientId . '&client_secret=' . $stravaClientSecret . '&code='.$stravaCode.'&grant_type=authorization_code';

        $test = $fetchService->post($stravaUrlReq);

        dd($test);

        return $this->redirectToRoute('app_dashboard');
    }
}
