<?php

namespace App\Controller;

use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/private/index')]
    public function index(DataService $dataService): JsonResponse
    {
        return $this->json([
            'data' => $dataService->getData(),
        ]);
    }
}
