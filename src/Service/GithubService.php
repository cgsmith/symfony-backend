<?php

namespace App\Service;

use App\Clients\GithubClient;
use App\Entity\Repo;
use App\Repository\RepoRepository;

class GithubService
{
    public function __construct(
        private GithubClient $githubClient,
        private RepoRepository $repoRepository
    ) {
    }

    public function create(string $name): Repo
    {
        $repo = new Repo();
        $repo->setName($name);

        $response = $this->githubClient->createRepo($repo);

        if (401 == $response->getStatusCode()) {
            throw new \Exception('GitHub returned a 401. Is your personal access token set in the .env file? See https://github.com/settings/tokens');
        } elseif (201 !== $response->getStatusCode()) {
            throw new \Exception('Something went wrong. Hopefully this helps: '.$response->getContent());
        }

        $object = json_decode($response->getContent(), true);
        $repo->setUrl($object['svn_url']);
        $repo->setFullName($object['full_name']);
        $repo->setRepoObject($object);
        $this->repoRepository->save($repo);

        return $repo;
    }

    public function delete(Repo $repo): void
    {
        $response = $this->githubClient->deleteRepo($repo);

        if (204 !== $response->getStatusCode()) {
            throw new \Exception('Something went wrong. Hopefully this helps: '.$response->getContent());
        }

        $this->repoRepository->remove($repo);
    }
}
