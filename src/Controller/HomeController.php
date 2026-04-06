<?php

namespace App\Controller;

use App\Entity\FeaturedItem;
use App\Enum\PageCode;
use App\Enum\FeaturedItemType;
use App\Entity\OpeningHour;
use App\Entity\Page;
use App\Entity\SiteSettings;
use App\Service\SiteMapBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, SiteMapBuilder $siteMapBuilder): Response
    {
        /** @var Page|null $page */
        $page = $entityManager->getRepository(Page::class)->findOneBy([
            'code' => PageCode::HOME,
            'isActive' => true,
        ]);
        /** @var SiteSettings|null $siteSettings */
        $siteSettings = $entityManager->getRepository(SiteSettings::class)->findOneBy([], ['id' => 'ASC']);
        /** @var list<FeaturedItem> $featuredItems */
        $featuredItems = null !== $siteSettings
            ? $entityManager->getRepository(FeaturedItem::class)->findBy([
                'siteSettings' => $siteSettings,
                'isActive' => true,
            ], [
                'position' => 'ASC',
                'id' => 'ASC',
            ])
            : [];

        $featuredItems = array_values(array_filter(
            $featuredItems,
            static function (FeaturedItem $featuredItem): bool {
                return match ($featuredItem->getType()) {
                    FeaturedItemType::PRODUCT => $featuredItem->getProduct()?->isAvailable() ?? false,
                    FeaturedItemType::CATEGORY => $featuredItem->getCategory()?->isActive() ?? false,
                    default => false,
                };
            }
        ));

        return $this->render('home/index.html.twig', [
            'featured_items' => $featuredItems,
            'page' => $page,
            'site_settings' => $siteSettings,
            'contact_map' => $siteSettings ? $siteMapBuilder->buildContactMap($siteSettings) : null,
            'itinerary_url' => $siteSettings ? $siteMapBuilder->buildItineraryUrl($siteSettings) : null,
            'opening_hours' => $siteSettings ? $this->sortOpeningHours($siteSettings) : [],
        ]);
    }

    private function sortOpeningHours(SiteSettings $siteSettings): array
    {
        $openingHours = $siteSettings->getOpeningHours()->toArray();

        usort($openingHours, static fn (OpeningHour $left, OpeningHour $right) => [$left->getPosition(), $left->getDayOfWeek()?->value] <=> [$right->getPosition(), $right->getDayOfWeek()?->value]);

        return $openingHours;
    }
}
