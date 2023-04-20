<?php

namespace App\Entity;

use App\Repository\RepoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepoRepository::class)]
class Repo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private array $repo_object = [];

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\ManyToOne(inversedBy: 'repos')]
    private ?User $githubUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getRepoObject(): array
    {
        return $this->repo_object;
    }

    public function setRepoObject(array $repo_object): self
    {
        $this->repo_object = $repo_object;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getGithubUser(): ?User
    {
        return $this->githubUser;
    }

    public function setGithubUser(?User $githubUser): self
    {
        $this->githubUser = $githubUser;

        return $this;
    }
}
