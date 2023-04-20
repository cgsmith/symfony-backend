<?php

namespace App\Clients;

use App\Entity\Repo;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Handles creating and deleting the repository with GitHub.
 */
class GithubClient
{
    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    /**
     * Creates GitHub repo.
     *
     * @see https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#create-a-repository-for-the-authenticated-user
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
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

    /**
     * Deletes GitHub repo.
     *
     * @see https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#delete-a-repository
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function deleteRepo(Repo $repo): ResponseInterface
    {
        return $this->client->request(
            'DELETE',
            '/repos/'.$repo->getFullName()
        );
    }
}
