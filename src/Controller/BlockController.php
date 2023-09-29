<?php

namespace App\Controller;

use App\DTO as DTO;
use App\Entity\Block;
use App\Repository\BlockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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

    #[Route('block/', methods: ['GET'])]
    public function getAll(BlockRepository $blockRepository): JsonResponse
    {
        $blocks = $blockRepository->findNotDeleted();
        return $this->json($this->serializer->normalize($blocks, null, $this->context));
    }

    #[Route('block/{id}', methods: ['GET'])]
    public function get(Block $block): JsonResponse
    {
        return $this->json($this->serializer->normalize($block, null, $this->context));
    }

    #[Route('block/{id}', methods: ['PUT'])]
    public function put(Block $block, #[MapRequestPayload] DTO\Block $dto, BlockRepository $blockRepository): JsonResponse
    {
        $dto->modifyEntity($block);
        $blockRepository->save($block, true);
        return $this->json(null, 204);
    }

    #[Route('block/', methods: ['POST'])]
    public function post(#[MapRequestPayload] DTO\Block $dto, BlockRepository $blockRepository): JsonResponse
    {
        $block = new Block();
        $dto->modifyEntity($block);
        $block->setDeleted(false);
        $blockRepository->save($block, true);
        return $this->json(null, 201);
    }

    #[Route('block/{id}', methods: ['DELETE'])]
    public function delete(Block $block, BlockRepository $blockRepository): JsonResponse
    {
        $block->setDeleted(true);
        $blockRepository->save($block, true);
        return $this->json(null, 204);
    }

    #[Route('block/{id}', methods: ['PATCH'])]
    public function patch(Block $block, BlockRepository $blockRepository): JsonResponse
    {
        $block->setDeleted(false);
        $blockRepository->save($block, true);
        return $this->json(null, 204);
    }
}
