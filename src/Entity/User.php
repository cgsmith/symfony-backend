<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'githubUser', targetEntity: Repo::class)]
    private Collection $repos;

    public function __construct()
    {
        $this->repos = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Repo>
     */
    public function getRepos(): Collection
    {
        return $this->repos;
    }

    public function addRepo(Repo $repo): self
    {
        if (!$this->repos->contains($repo)) {
            $this->repos->add($repo);
            $repo->setGithubUser($this);
        }

        return $this;
    }

    public function removeRepo(Repo $repo): self
    {
        if ($this->repos->removeElement($repo)) {
            // set the owning side to null (unless already changed)
            if ($repo->getGithubUser() === $this) {
                $repo->setGithubUser(null);
            }
        }

        return $this;
    }
}
