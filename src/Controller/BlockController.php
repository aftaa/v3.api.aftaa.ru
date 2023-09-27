<?php

namespace App\Controller;

use App\Entity\Block;
use App\Repository\BlockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/private/')]
class BlockController extends AbstractController
{
    private array $context;

    public function __construct(
        private readonly SerializerInterface $serializer,
    )
    {
        $this->context = (new ObjectNormalizerContextBuilder())->withGroups('api')->toArray();
    }

    #[Route('block/{id}', methods: ['GET'])]
    public function get(Block $block): JsonResponse
    {
        return $this->json($this->serializer->normalize($block, null, $this->context));
    }

    #[Route('block/{id}', methods: ['PUT'])]
    public function post(Block $block, Request $request, BlockRepository $blockRepository): Response
    {
//        $data = $this->serializer->deserialize($request->getPayload(), BlockDTO::class, 'json');
        $data = json_decode($request->getContent(), true);
        foreach ($data as $key => $value) {
            $block->{'set' . ucfirst($key)}($value);
        }
        $blockRepository->save($block, true);
        return $this->json(null, 204);
    }
}
