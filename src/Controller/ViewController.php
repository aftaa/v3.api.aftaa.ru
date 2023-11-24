<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\View;
use App\Repository\ViewRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag('Views')]
#[Security(name: 'Bearer')]
#[OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'JWT токен не передан или не верен')]
class ViewController extends AbstractController
{
    /**
     * @param Link $link
     * @param ViewRepository $viewRepository
     * @param Request $request
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    #[Route('/private/view/{id}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Количество просмотров',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "views", type: "integer")
            ],
            type: 'object',
        )
    )]
    public function view(Link $link, ViewRepository $viewRepository, Request $request): JsonResponse
    {
        $view = new View();
        $view->setDateTime(new \DateTime('now'));
        $view->setIp4(ip2long($request->server->get('REMOTE_ADDR')));
        $view->setLink($link);
        $viewRepository->save($view, true);

        $views = $viewRepository->findByLink($link->getId());
        return $this->json([
            'views' => $views,

        ], Response::HTTP_OK);
    }
}
