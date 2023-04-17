<?php

namespace App\Interfaces;

use App\Domain\User;

interface UserRepositoryInterface
{
    public function findByUsername(string $username): ?User;
}
