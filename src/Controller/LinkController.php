<?php

namespace App\Controller;

use App\DTO as DTO;
use App\Entity\Link;
use App\Repository\BlockRepository;
use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class LinkController extends AbstractController
{
    private array $context;

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly LinkRepository $linkRepository,
        private readonly BlockRepository $blockRepository,
    )
    {
        $this->context = (new ObjectNormalizerContextBuilder())->withGroups('api')->toArray();
    }

    #[Route('/private/link/{id}', methods: ['GET'])]
    public function get(Link $link): JsonResponse
    {
        return $this->json($this->serializer->normalize($link, null, $this->context));
    }

    #[Route('/private/link/{id}', methods: ['PUT'])]
    public function put(Link $link, #[MapRequestPayload] DTO\Link $dto): JsonResponse
    {
        $dto->modifyEntity($link, ['block_id']);
        $link->setBlock($this->blockRepository->find($dto->block_id));
        $this->linkRepository->save($link, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/private/link/', methods: ['POST'])]
    public function post(#[MapRequestPayload] DTO\Link $dto): JsonResponse
    {
        $link = new Link();
        $dto->modifyEntity($link, ['block_id']);
        $link->setBlock($this->blockRepository->find($dto->block_id));
        $link->setDeleted(false);
        $this->linkRepository->save($link, true);
        return $this->json(['id' => $link->getId()], Response::HTTP_CREATED);
    }

    #[Route('/private/link/{id}', methods: ['DELETE'])]
    public function delete(Link $link): JsonResponse
    {
        $link->setDeleted(true);
        $this->linkRepository->save($link, true);
        return $this->json(null, 204);
    }

    #[Route('/private/link/{id}', methods: ['PATCH'])]
    public function patch(Link $link): JsonResponse
    {
        $link->setDeleted(false);
        $this->linkRepository->save($link, true);
        return $this->json(null, 204);
    }
}
