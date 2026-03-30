<?php

namespace App\Controller;

use App\Enum\PageCode;
use App\Entity\Page;
use App\Entity\SiteSettings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AboutController extends AbstractController
{
    #[Route('/a-propos', name: 'app_about', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        /** @var Page|null $page */
        $page = $entityManager->getRepository(Page::class)->findOneBy([
            'code' => PageCode::ABOUT,
            'isActive' => true,
        ]);

        /** @var SiteSettings|null $siteSettings */
        $siteSettings = $entityManager->getRepository(SiteSettings::class)->findOneBy([], ['id' => 'ASC']);

        return $this->render('about/index.html.twig', [
            'page' => $page,
            'site_settings' => $siteSettings,
        ]);
    }
}
