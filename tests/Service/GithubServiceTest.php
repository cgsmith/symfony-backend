<?php
namespace App\Tests\Service;

use App\Clients\GithubClient;
use App\Entity\Repo;
use App\Entity\User;
use App\Repository\RepoRepository;
use App\Repository\UserRepository;
use App\Service\GithubService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;

class GithubServiceTest extends KernelTestCase
{
    private GithubService $githubService;
    private RepoRepository $repoRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = self::getContainer();

        $this->githubService = $container->get(GithubService::class);
        $this->repoRepository = $container->get(RepoRepository::class);
        $this->userRepository = $container->get(UserRepository::class);
    }

    public function testCreateSucceed(): void
    {
        $name = 'Hello-World';

        // create mock - 201
        $githubService = $this->createMock(GithubClient::class);
        $githubService->expects(self::once())
            ->method('createRepo')
            ->willReturn(new MockResponse('{"name": "Hello-World","full_name": "octocat/Hello-World","owner": {"login": "octocat"}}'));

        // Create a new repository
        $repo = $this->githubService->create($name);

        // Check that the repository was saved to the database
        $this->assertNotNull($repo->getId());
        $this->assertSame($name, $repo->getName());
        $this->assertNotNull($repo->getUrl());
        $this->assertNotNull($repo->getFullName());
        $this->assertInstanceOf(User::class, $repo->getGithubUser());

        // Check that the user was saved to the database
        $user = $this->userRepository->findOneByName($repo->getGithubUser()->getName());
        $this->assertNotNull($user);
        $this->assertSame($repo->getGithubUser()->getName(), $user->getName());

        // Delete the repository
        $this->githubService->delete($repo->getFullName());

        // Check that the repository was deleted from the database
        $repo = $this->repoRepository->findOneByName($name);
        $this->assertNull($repo);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Remove all entities from the database to ensure that each test starts with a clean slate
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->createQuery('DELETE FROM '.User::class)->execute();
        $entityManager->createQuery('DELETE FROM '.Repo::class)->execute();
    }
}
