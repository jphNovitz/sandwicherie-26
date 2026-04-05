<?php

namespace App\Service;

use App\Entity\SiteSettings;
use Doctrine\ORM\EntityManagerInterface;

final class SiteSettingsProvider
{
    private ?SiteSettings $siteSettings = null;
    private bool $loaded = false;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getSiteSettings(): ?SiteSettings
    {
        if ($this->loaded) {
            return $this->siteSettings;
        }

        /** @var SiteSettings|null $siteSettings */
        $siteSettings = $this->entityManager
            ->getRepository(SiteSettings::class)
            ->findOneBy([], ['id' => 'ASC']);

        $this->siteSettings = $siteSettings;
        $this->loaded = true;

        return $this->siteSettings;
    }
}
