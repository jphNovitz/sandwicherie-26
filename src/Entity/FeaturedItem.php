<?php

namespace App\Entity;

use App\Enum\FeaturedItemType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'featured_item')]
class FeaturedItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'featuredItems')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?SiteSettings $siteSettings = null;

    #[ORM\Column(length: 20, enumType: FeaturedItemType::class)]
    private ?FeaturedItemType $type = null;

    #[ORM\Column]
    private int $position = 0;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customTitle = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $shortText = null;

    #[ORM\Column]
    private bool $showPrice = false;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Product $product = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Category $category = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiteSettings(): ?SiteSettings
    {
        return $this->siteSettings;
    }

    public function setSiteSettings(?SiteSettings $siteSettings): static
    {
        $this->siteSettings = $siteSettings;

        return $this;
    }

    public function getType(): ?FeaturedItemType
    {
        return $this->type;
    }

    public function setType(FeaturedItemType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getCustomTitle(): ?string
    {
        return $this->customTitle;
    }

    public function setCustomTitle(?string $customTitle): static
    {
        $this->customTitle = $customTitle;

        return $this;
    }

    public function getShortText(): ?string
    {
        return $this->shortText;
    }

    public function setShortText(?string $shortText): static
    {
        $this->shortText = $shortText;

        return $this;
    }

    public function isShowPrice(): bool
    {
        return $this->showPrice;
    }

    public function setShowPrice(bool $showPrice): static
    {
        $this->showPrice = $showPrice;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
