<?php

namespace App\Controller;

use App\Entity\FeaturedItem;
use App\Enum\PageCode;
use App\Enum\FeaturedItemType;
use App\Entity\Page;
use App\Entity\SiteSettings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
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
        ]);
    }
}
