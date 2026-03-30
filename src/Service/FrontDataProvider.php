<?php

namespace App\Service;

use App\Enum\PageCode;
use App\Entity\Category;
use App\Entity\Page;
use App\Entity\SiteSettings;
use Doctrine\ORM\EntityManagerInterface;

final class FrontDataProvider
{
    private bool $siteSettingsLoaded = false;
    private ?SiteSettings $siteSettings = null;

    /** @var array<string, Page|null> */
    private array $pagesByCode = [];

    /** @var array<int, array{category: Category, products: array}>|null */
    private ?array $menuCategories = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getSiteSettings(): ?SiteSettings
    {
        if (!$this->siteSettingsLoaded) {
            $this->siteSettings = $this->entityManager->getRepository(SiteSettings::class)->findOneBy([], ['id' => 'ASC']);
            $this->siteSettingsLoaded = true;
        }

        return $this->siteSettings;
    }

    public function getPage(PageCode $code): ?Page
    {
        $cacheKey = $code->value;

        if (!array_key_exists($cacheKey, $this->pagesByCode)) {
            $this->pagesByCode[$cacheKey] = $this->entityManager->getRepository(Page::class)->findOneBy([
                'code' => $code,
                'isActive' => true,
            ]);
        }

        return $this->pagesByCode[$cacheKey];
    }

    public function getLegalPages(): array
    {
        $pages = [];

        foreach ([PageCode::LEGAL, PageCode::PRIVACY, PageCode::COOKIES] as $code) {
            $page = $this->getPage($code);

            if ($page instanceof Page) {
                $pages[] = $page;
            }
        }

        return $pages;
    }

    public function getOpeningHours(): array
    {
        $siteSettings = $this->getSiteSettings();

        if (!$siteSettings instanceof SiteSettings) {
            return [];
        }

        $openingHours = $siteSettings->getOpeningHours()->toArray();

        usort($openingHours, static fn ($left, $right) => [$left->getPosition(), $left->getDayOfWeek()?->value] <=> [$right->getPosition(), $right->getDayOfWeek()?->value]);

        return $openingHours;
    }

    public function getSocialLinks(): array
    {
        $siteSettings = $this->getSiteSettings();

        if (!$siteSettings instanceof SiteSettings) {
            return [];
        }

        $socialLinks = array_filter(
            $siteSettings->getSocialLinks()->toArray(),
            static fn ($socialLink) => $socialLink->isActive()
        );

        usort($socialLinks, static fn ($left, $right) => [$left->getPosition(), $left->getId()] <=> [$right->getPosition(), $right->getId()]);

        return $socialLinks;
    }

    public function getVisibleReviews(int $limit = 3): array
    {
        $siteSettings = $this->getSiteSettings();

        if (!$siteSettings instanceof SiteSettings) {
            return [];
        }

        $reviews = array_filter(
            $siteSettings->getReviews()->toArray(),
            static fn ($review) => $review->isVisible()
        );

        usort($reviews, static fn ($left, $right) => [$left->getPosition(), $left->getId()] <=> [$right->getPosition(), $right->getId()]);

        return array_slice($reviews, 0, $limit);
    }

    public function getFeaturedItems(int $limit = 6): array
    {
        $siteSettings = $this->getSiteSettings();

        if (!$siteSettings instanceof SiteSettings) {
            return [];
        }

        $featuredItems = array_filter(
            $siteSettings->getFeaturedItems()->toArray(),
            static fn ($featuredItem) => $featuredItem->isActive()
        );

        usort($featuredItems, static fn ($left, $right) => [$left->getPosition(), $left->getId()] <=> [$right->getPosition(), $right->getId()]);

        return array_slice($featuredItems, 0, $limit);
    }

    public function getGalleryImages(int $limit = 4): array
    {
        $siteSettings = $this->getSiteSettings();

        if (!$siteSettings instanceof SiteSettings) {
            return [];
        }

        $galleryImages = array_filter(
            $siteSettings->getGalleryImages()->toArray(),
            static fn ($galleryImage) => $galleryImage->isActive()
        );

        usort($galleryImages, static fn ($left, $right) => [$left->getPosition(), $left->getId()] <=> [$right->getPosition(), $right->getId()]);

        return array_slice($galleryImages, 0, $limit);
    }

    public function getAboutHighlights(int $limit = 4): array
    {
        $siteSettings = $this->getSiteSettings();

        if (!$siteSettings instanceof SiteSettings) {
            return [];
        }

        $aboutHighlights = array_filter(
            $siteSettings->getAboutHighlights()->toArray(),
            static fn ($aboutHighlight) => $aboutHighlight->isActive()
        );

        usort($aboutHighlights, static fn ($left, $right) => [$left->getPosition(), $left->getId()] <=> [$right->getPosition(), $right->getId()]);

        return array_slice($aboutHighlights, 0, $limit);
    }

    public function getMenuCategories(): array
    {
        if (null !== $this->menuCategories) {
            return $this->menuCategories;
        }

        $categories = $this->entityManager->getRepository(Category::class)->findBy([
            'isActive' => true,
        ], [
            'position' => 'ASC',
            'id' => 'ASC',
        ]);

        $menuCategories = [];

        foreach ($categories as $category) {
            $products = array_filter(
                $category->getProducts()->toArray(),
                static fn ($product) => $product->isAvailable()
            );

            usort($products, static fn ($left, $right) => [$left->getPosition(), $left->getId()] <=> [$right->getPosition(), $right->getId()]);

            if ([] === $products) {
                continue;
            }

            $menuCategories[] = [
                'category' => $category,
                'products' => $products,
            ];
        }

        $this->menuCategories = $menuCategories;

        return $this->menuCategories;
    }
}
