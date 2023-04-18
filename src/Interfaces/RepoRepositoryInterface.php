<?php

namespace App\Interfaces;

use App\Entity\Repo;
use App\Entity\User;

interface RepoRepositoryInterface
{
    public function create(User $user, string $repositoryName): Repo;
}
