<?php

namespace App\Interfaces;

use App\Domain\Repository;
use App\Domain\User;

interface RepositoryRepositoryInterface
{
    public function createForUser(User $user, string $repositoryName): Repository;
}
