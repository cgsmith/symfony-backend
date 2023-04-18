<?php

namespace App\Clients;

use App\Entity\Repo;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GithubClient
{
    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    public function createRepo(Repo $repo): ResponseInterface
    {
        return $this->client->request(
            'POST',
            '/user/repos', [
                'json' => [
                    'name' => $repo->getName(),
                ],
            ]
        );
    }

    public function deleteRepo(Repo $repo): ResponseInterface
    {
    }
}
