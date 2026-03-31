<?php

namespace App\Controller;

use App\Enum\PageCode;
use App\Entity\Page;
use App\Entity\SiteSettings;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        /** @var Page|null $page */
        $page = $entityManager->getRepository(Page::class)->findOneBy([
            'code' => PageCode::HOME,
            'isActive' => true,
        ]);
        /** @var SiteSettings|null $siteSettings */
        $siteSettings = $entityManager->getRepository(SiteSettings::class)->findOneBy([], ['id' => 'ASC']);

        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findBy(['isAvailable' => true], ['id' => 'DESC']),
            'page' => $page,
            'site_settings' => $siteSettings,
        ]);
    }
}
