<?php

namespace App\Controller;

use App\Enum\PageCode;
use App\Service\FrontDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MenuController extends AbstractController
{
    #[Route('/carte', name: 'app_menu', methods: ['GET'])]
    public function index(FrontDataProvider $frontDataProvider): Response
    {
        return $this->render('menu/index.html.twig', [
            'page' => $frontDataProvider->getPage(PageCode::MENU),
            'menu_categories' => $frontDataProvider->getMenuCategories(),
        ]);
    }
}
