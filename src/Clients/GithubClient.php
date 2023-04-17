<?php

namespace App\Clients;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GithubClient
{
    public function __construct(
        private HttpClientInterface $client
    ){
    }

    public function createRepo(string $name): ResponseInterface {
        return $this->client->request(
            'POST',
            '/user/repos', [
                'json' => [
                    'name' => $name
                ]
            ]
        );
    }
}
