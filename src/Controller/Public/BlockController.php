<?php

namespace App\Controller\Public;

use App\Repository\BlockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/public/')]
class BlockController extends AbstractController
{
    #[Route('blocks', name: 'app_public_blocks', methods: ['GET'])]
    public function blocks(BlockRepository $blockRepository): JsonResponse
    {
        return $this->json([
            'user' => $this->getUser()?->getUserIdentifier(),
        ]);
    }
}
