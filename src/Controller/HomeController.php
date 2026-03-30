<?php

namespace App\Controller;

use App\Service\FrontDataProvider;
use App\Service\SiteMapBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(FrontDataProvider $frontDataProvider, SiteMapBuilder $siteMapBuilder): Response
    {
        $siteSettings = $frontDataProvider->getSiteSettings();

        return $this->render('home/index.html.twig', [
            'page' => $frontDataProvider->getPage(\App\Enum\PageCode::HOME),
            'site_settings' => $siteSettings,
            'featured_items' => $frontDataProvider->getFeaturedItems(),
            'opening_hours' => $frontDataProvider->getOpeningHours(),
            'reviews' => $frontDataProvider->getVisibleReviews(),
            'gallery_images' => $frontDataProvider->getGalleryImages(),
            'itinerary_url' => $siteSettings ? $siteMapBuilder->buildItineraryUrl($siteSettings) : null,
        ]);
    }
}
