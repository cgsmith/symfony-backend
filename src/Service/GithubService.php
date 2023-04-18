<?php

namespace App\Service;

use App\Clients\GithubClient;
use App\Repository\RepoRepository;

class GithubService
{
    public function __construct(
        private GithubClient $githubClient,
        private RepoRepository $repoRepository
    ) {
    }

    public function execute(string $name, string $description)
    {
    }
}
