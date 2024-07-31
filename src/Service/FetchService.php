<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class FetchService
{
    public function __construct(
        private HttpClientInterface $httpClient
    )
    {
    }

    public function get(string $url): ResponseInterface
    {
        return $this->httpClient->request('GET', $url);
    }

    public function post(string $url): ResponseInterface
    {
        return $this->httpClient->request('POST', $url);
    }
}
