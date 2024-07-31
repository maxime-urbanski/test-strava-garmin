<?php

namespace App\Controller;

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

    const STRAVA_URL_LOGIN = 'https://www.strava.com/oauth/token'; # http://www.strava.com/oauth/authorize?client_id=131358&response_type=code&redirect_uri=http://localhost/exchange_token&approval_prompt=force&scope=read
    const STRAVA_CLIENT_ID = '131358';
    const STRAVA_TOKEN_ID = '42d52fa849055b4ef791f4d43ba627d08126434c';
    const STRAVA_REFRESH_TOKEN_ID = '46de131914cb9f051e7c764ecdfe688ac4c9dc17';

    public function __invoke(
        Request $request,
        HttpClientInterface $client
    ): Response
    {
        $stravaCode = $request->get('code');
        dump($stravaCode);
        $stravaUrlReq = self::STRAVA_URL_LOGIN . '?client_id=' . self::STRAVA_CLIENT_ID . '&client_secret=' . self::STRAVA_TOKEN_ID . '&code='.$stravaCode.'&grant_type=authorization_code';
        dump($stravaUrlReq);
        die;
        $test = $client->request('GET', $stravaUrlReq);
        dump($test);

        return $this->render('exchange_token/index.html.twig', [
            'controller_name' => 'ExchangeTokenController',
        ]);
    }
}
