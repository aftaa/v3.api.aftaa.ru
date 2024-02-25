<?php

namespace App\Controller;

use App\Repository\BlockRepository;
use App\Repository\ViewRepository;
use App\Service\AdminDataService;
use App\Service\IndexDataService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Query\QueryException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag('Data')]
#[Security(name: 'Bearer')]
#[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'JWT токен не передан или не верен')]
class DataController extends AbstractController
{
    #[Route('/public/data/index', methods: ['GET'])]
    public function index(IndexDataService $indexDataService): JsonResponse
    {
        return $this->json($indexDataService->getPublicData());
    }
    /**
     * @param IndexDataService $indexDataService
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('/private/data/index', methods: ['GET'])]
    public function private(IndexDataService $indexDataService): JsonResponse
    {
        return $this->json([
            'data' => $indexDataService->getPrivateData(),
        ]);
    }

    /**
     * @param ViewRepository $viewRepository
     * @return JsonResponse
     */
    #[Route('/private/data/last', methods: 'GET')]
    public function last(ViewRepository $viewRepository): JsonResponse
    {
        return $this->json($viewRepository->findRecent(7));
    }

    /**
     * @param AdminDataService $adminDataService
     * @return JsonResponse
     * @throws QueryException
     */
    #[Route('/private/data/admin', methods: ['GET'])]
    public function admin(AdminDataService $adminDataService): JsonResponse
    {
        return $this->json($adminDataService->getPrivateData());
    }

    #[Route('/public/blocks', methods: ['GET'])]
    public function blocks(BlockRepository $blockRepository)
    {
        return $this->json($blockRepository->findBlocksOnly());
    }
}
