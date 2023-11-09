<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Model\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag('Test')]
class TestController extends AbstractController
{
    #[Route('/private/test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        return $this->json([
            'user' => $this->getUser()?->getUserIdentifier(),
        ]);
    }
}
