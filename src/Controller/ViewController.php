<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\View;
use App\Repository\ViewRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        ]);
    }
}
