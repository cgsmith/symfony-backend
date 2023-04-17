<?php

namespace App\Domain;

class Repository
{

    public function __construct(
        private string $name,
        private string $url
    ){}

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
