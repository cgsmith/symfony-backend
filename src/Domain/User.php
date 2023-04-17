<?php

namespace App\Domain;


class User
{
    public function __construct(
        private string $username,
        private string $accessToken
    ) {}

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
