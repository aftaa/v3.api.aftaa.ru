<?php

namespace App\Controller;

use App\DTO as DTO;
use App\Entity\Block;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Repository\BlockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/private/')]
#[OA\Tag(name: 'Blocks')]
#[Security(name: 'Bearer')]
#[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'JWT токен не передан или не верен')]
#[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Внутренняя ошибка сервера')]
class BlockController extends AbstractController
{
    private array $context;

    public function __construct(
        private readonly SerializerInterface $serializer,
    )
    {
        $this->context = (new ObjectNormalizerContextBuilder())->withGroups('api')->toArray();
    }

    /**
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('block/', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Список блоков', content: new OA\JsonContent(
        type: 'array', items: new OA\Items(ref: new Model(type: Block::class, groups: ['block']))
    ))]
    public function getAll(BlockRepository $blockRepository): JsonResponse
    {
        $blocks = $blockRepository->findNotDeleted();
        return $this->json($this->serializer->normalize($blocks, null, $this->context));
    }

    #[Route('block/{id}', methods: ['GET'])]
    #[OA\Response(response: Response::HTTP_OK, description: 'Блок', content: new OA\JsonContent(
            ref: new Model(type: Block::class, groups: ['block'])))]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Блок не найден')]
    public function get(Block $block): JsonResponse
    {
        return $this->json($this->serializer->normalize($block, null, $this->context));
    }
    /**
     * @param Block $block
     * @param DTO\Block $dto
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('block/{id}', methods: ['PUT'])]
    #[OA\RequestBody(content: new Model(type: DTO\Block::class))]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Блок изменен')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Блок не найден')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Неверные входные данные')]
    public function put(Block $block, #[MapRequestPayload] DTO\Block $dto, BlockRepository $blockRepository): JsonResponse
    {
        $dto->modifyEntity($block);
        $blockRepository->save($block, true);
        return $this->json(null, 204);
    }

    /**
     * @param DTO\Block $dto
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('block/', methods: ['POST'])]
    #[OA\RequestBody(content: new Model(type: DTO\Block::class))]
    #[OA\Response(response: Response::HTTP_CREATED, description: 'Блок добавлен')]
    #[OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Неверные входные данные')]
    public function post(#[MapRequestPayload] DTO\Block $dto, BlockRepository $blockRepository): JsonResponse
    {
        $block = new Block();
        $dto->modifyEntity($block);
        $block->setDeleted(false);
        $blockRepository->save($block, true);
        return $this->json(['id' => $block->getId()], Response::HTTP_CREATED);
    }

    /**
     * @param Block $block
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('block/{id}', methods: ['DELETE'])]
    #[OA\Response(response: Response::HTTP_CREATED, description: 'Блок удален')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Блок не найден')]
    public function delete(Block $block, BlockRepository $blockRepository): JsonResponse
    {
        $block->setDeleted(true);
        $blockRepository->save($block, true);
        return $this->json(null);
    }

    /**
     * @param Block $block
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     */
    #[Route('block/{id}', methods: ['PATCH'])]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Блок восстановлен')]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Блок не найден')]
    public function patch(Block $block, BlockRepository $blockRepository): JsonResponse
    {
        $block->setDeleted(false);
        $blockRepository->save($block, true);
        return $this->json(null, 204);
    }
}
