<?php

namespace App\Service;

use App\Clients\GithubClient;
use App\Entity\Repo;
use App\Entity\User;
use App\Repository\RepoRepository;
use App\Repository\UserRepository;

/**
 * Responsible for calling GitHub and also handles saving Entity creation
 */
class GithubService
{
    public function __construct(
        private GithubClient $githubClient,
        private RepoRepository $repoRepository,
        private UserRepository $userRepository
    ) {
    }

    /**
     * Creates a repo under the personal access token OWNER
     *
     * @param string $name repo-name
     * @return Repo
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
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

        // could use a mapper here
        $object = json_decode($response->getContent(), true);

        // set user
        $user = new User();
        $user->setName($object['owner']['login']);
        $this->userRepository->save($user);

        // set repo
        $repo->setUrl($object['svn_url']);
        $repo->setFullName($object['full_name']);
        $repo->setRepoObject($object);
        $repo->setGithubUser($user);
        $this->repoRepository->save($repo);

        return $repo;
    }

    /**
     * Delete repository by the fullname
     *
     * @param string $repoFullName OWNER/repo-name
     * @return void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function delete(string $repoFullName): void
    {
        $repo = $this->repoRepository->findOneByFullName($repoFullName);

        if (empty($repo)) {
            throw new \Exception('Not found - try the full_name shown on the API');
        }

        $response = $this->githubClient->deleteRepo($repo);

        if (204 !== $response->getStatusCode()) {
            throw new \Exception('Something went wrong. Hopefully this helps: '.$response->getContent());
        }

        $this->repoRepository->remove($repo);
    }
}
