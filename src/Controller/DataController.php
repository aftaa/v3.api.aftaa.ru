<?php

namespace App\Controller;

use App\Service\AdminDataService;
use App\Service\IndexDataService;
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
