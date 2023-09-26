<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\View;
use App\Repository\ViewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkController extends AbstractController
{
    #[Route('/private/link/view/{id}')]
    public function index(Link $link, ViewRepository $viewRepository, Request $request): Response
    {
        $view = new View();
        $view->setDateTime(new \DateTime('now'));
        $view->setIp4(ip2long($request->server->get('REMOTE_ADDR')));
        $view->setLink($link);
        $viewRepository->save($view, true);
        return new Response('');
    }
}
