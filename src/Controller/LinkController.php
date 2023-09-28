<?php

namespace App\Controller;

use App\DTO as DTO;
use App\Entity\Link;
use App\Entity\View;
use App\Repository\LinkRepository;
use App\Repository\ViewRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $dto->modifyEntity($link);
        $this->linkRepository->save($link, true);
        return $this->json(null, 204);
    }

    #[Route('/private/link/', methods: ['POST'])]
    public function post(#[MapRequestPayload] DTO\Link $dto): JsonResponse
    {
        $link = new Link();
        $dto->modifyEntity($link);
        $link->setDeleted(false);
        $this->linkRepository->save($link, true);
        return $this->json(null, 201);
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
