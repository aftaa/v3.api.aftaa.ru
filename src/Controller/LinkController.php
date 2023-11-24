<?php

namespace App\Controller;

use App\DTO as DTO;
use App\Entity\Block;
use App\Entity\Link;
use App\Repository\BlockRepository;
use App\Repository\LinkRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[OA\Tag(name: 'Ссылки')]
#[Security(name: 'Bearer')]
#[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'JWT токен не передан или не верен')]
#[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Внутренняя ошибка сервера')]
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
    #[OA\Response(response: Response::HTTP_OK, description: 'Link', content: new OA\JsonContent(
        ref: new Model(type: Link::class, groups: ['link'])))]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Ссылка не найдена')]
    public function get(Link $link): JsonResponse
    {
        return $this->json($this->serializer->normalize($link, null, $this->context));
    }

    #[Route('/private/link/{id}', methods: ['PUT'])]
    #[OA\RequestBody(content: new Model(type: DTO\Link::class))]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Ссылка изменена')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Ссылка не найдена')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Неверные входные данные')]
    public function put(Link $link, #[MapRequestPayload] DTO\Link $dto): JsonResponse
    {
        $dto->modifyEntity($link, ['block_id']);
        $link->setBlock($this->blockRepository->find($dto->block_id));
        $this->linkRepository->save($link, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/private/link/', methods: ['POST'])]
    #[OA\RequestBody(content: new Model(type: DTO\Link::class))]
    #[OA\Response(response: Response::HTTP_CREATED, description: 'Ссылка добавлена')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Неверные входные данные')]
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
    #[OA\Response(response: Response::HTTP_CREATED, description: 'Ссылка удалена')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Ссылка не найдена')]
    public function delete(Link $link): JsonResponse
    {
        $link->setDeleted(true);
        $this->linkRepository->save($link, true);
        return $this->json(null, 204);
    }

    #[Route('/private/link/{id}', methods: ['PATCH'])]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Ссылка восстановлена')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Ссылка не найдена')]
    public function patch(Link $link): JsonResponse
    {
        $link->setDeleted(false);
        $this->linkRepository->save($link, true);
        return $this->json(null, 204);
    }
}
