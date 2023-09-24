<?php

namespace App\Controller\Private;

use App\Repository\BlockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/private/')]
class BlockController extends AbstractController
{
    #[Route('blocks', name: 'app_private_blocks', methods: ['POST'])]
    public function blocks(BlockRepository $blockRepository): JsonResponse
    {
        return $this->json([
            'user' => $this->getUser()?->getUserIdentifier(),
        ]);
    }
}
