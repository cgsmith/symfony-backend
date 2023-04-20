<?php

namespace App\Controller;

use App\Entity\Repo;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/repos')]
class RepoController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'app_api_repos', methods: ['GET'])]
    #[OA\Parameter(
        name: 'fullName',
        in: 'query',
        description: 'Use this field to query by name',
        schema: new OA\Schema(type: 'string')
    )]
    public function index(Request $request): JsonResponse
    {
        $fullName = $request->query->get('fullName');

        $qb = $this->entityManager->getRepository(Repo::class)->createQueryBuilder('r');

        if ($fullName) {
            $qb->where('r.fullName LIKE :fullName')->setParameter('fullName', '%'.$fullName.'%');
        }

        $repos = $qb->getQuery()->getResult();

        $data = [];
        /** @var Repo $repo */
        foreach ($repos as $repo) {
            $data[] = [
                'id' => $repo->getId(),
                'name' => $repo->getName(),
                'url' => $repo->getUrl(),
                'full_name' => $repo->getFullName(),
                'repo_object' => $repo->getRepoObject(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/{id}', name: 'app_api_repo', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $repo = $this->entityManager->getRepository(Repo::class)->find($id);
        if (!$repo) {
            return new JsonResponse(['error' => 'Repo not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $repo->getId(),
            'name' => $repo->getName(),
            'url' => $repo->getUrl(),
            'full_name' => $repo->getFullName(),
            'repo_object' => $repo->getRepoObject(),
        ];

        return new JsonResponse($data);
    }
}
