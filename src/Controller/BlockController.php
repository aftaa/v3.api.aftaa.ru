<?php

namespace App\Controller;

use App\Entity\Block;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BlockController extends AbstractController
{
    #[Route('/private/blocks/{id}', methods: ['GET'])]
    public function get(Block $block): JsonResponse
    {
        $block = [
            'id' => $block->getId(),
            'name' => $block->getName(),
            'col' => $block->getCol(),
            'sort' => $block->getSort(),
            'private' => $block->isPrivate(),
        ];
        return $this->json($block);
    }
}
