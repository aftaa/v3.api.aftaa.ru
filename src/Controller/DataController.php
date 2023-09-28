<?php

namespace App\Controller;

use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    #[Route('/private/data/index')]
    public function index(DataService $dataService): JsonResponse
    {
        return $this->json([
            'data' => $dataService->getIndexData(),
        ]);
    }
    #[Route('/private/data/admin')]
    public function admin(DataService $dataService): JsonResponse
    {
        return $this->json($dataService->getAdminData());
    }
    #[Route('/public/data/index')]
    public function publicIndex(DataService $dataService): JsonResponse
    {
        $indexData = $dataService->getIndexData();
        dump($indexData);
        return $this->json($indexData);
    }
    #[Route('/public/data/admin')]
    public function publicAdmin(DataService $dataService): JsonResponse
    {
        $adminData = $dataService->getAdminData();
        dump($adminData);
        return $this->json([
            $adminData,
        ]);
    }
}
