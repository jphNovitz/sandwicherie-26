<?php

namespace App\Controller;

use App\Enum\PageCode;
use App\Entity\AboutHighlight;
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
        $aboutHighlights = $siteSettings ? $this->sortActiveHighlights($siteSettings) : [];

        return $this->render('about/index.html.twig', [
            'about_highlights' => $aboutHighlights,
            'page' => $page,
            'site_settings' => $siteSettings,
        ]);
    }

    private function sortActiveHighlights(SiteSettings $siteSettings): array
    {
        $highlights = array_filter(
            $siteSettings->getAboutHighlights()->toArray(),
            static fn (AboutHighlight $highlight): bool => $highlight->isActive()
        );

        usort($highlights, static fn (AboutHighlight $left, AboutHighlight $right) => [$left->getPosition(), $left->getId()] <=> [$right->getPosition(), $right->getId()]);

        return $highlights;
    }
}
