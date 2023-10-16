<?php

namespace App\Controller;

use App\Repository\ViewRepository;
use App\Service\AdminDataService;
use App\Service\IndexDataService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Query\QueryException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    /**
     * @param IndexDataService $indexDataService
     * @return JsonResponse
     */
    #[Route('/private/data/index')]
    public function index(IndexDataService $indexDataService): JsonResponse
    {
        return $this->json([
            'data' => $indexDataService->getData(),
        ]);
    }

    /**
     * @param ViewRepository $viewRepository
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('/private/data/last')]
    public function last(ViewRepository $viewRepository): JsonResponse
    {
        return $this->json($viewRepository->findLast(7));
    }

    /**
     * @param AdminDataService $adminDataService
     * @return JsonResponse
     * @throws QueryException
     */
    #[Route('/private/data/admin')]
    public function admin(AdminDataService $adminDataService): JsonResponse
    {
        return $this->json($adminDataService->getData());
    }
}
