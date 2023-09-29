<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/private/test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        return $this->json([
            $this->getUser()?->getUserIdentifier(),
        ]);
    }
}
