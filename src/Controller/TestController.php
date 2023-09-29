<?php

namespace App\Controller;

use App\Repository\BlockRepository;
use App\Repository\LinkRepository;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/public/test')]
    public function testData(BlockRepository $blockRepository): Response
    {
        $data = $blockRepository->findNotDeletedSortedByNameIndexedById();
//        dump($data);
        echo '<pre>'; print_r($data);
        return new Response('');
    }
}
