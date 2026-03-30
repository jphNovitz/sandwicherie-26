<?php

namespace App\Entity;

use App\Enum\FeaturedItemType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity]
#[ORM\Table(name: 'featured_item')]
class FeaturedItem
{
    public const MAX_ACTIVE_ITEMS = 6;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'featuredItems')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?SiteSettings $siteSettings = null;

    #[ORM\Column(length: 20, enumType: FeaturedItemType::class)]
    #[Assert\NotNull]
    private ?FeaturedItemType $type = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
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
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Product $product = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
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

    public function __toString(): string
    {
        return $this->getDisplayTarget();
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

    public function getCustomText(): ?string
    {
        return $this->shortText;
    }

    public function setShortText(?string $shortText): static
    {
        $this->shortText = $shortText;

        return $this;
    }

    public function setCustomText(?string $customText): static
    {
        return $this->setShortText($customText);
    }

    public function isShowPrice(): bool
    {
        return $this->showPrice;
    }

    public function isDisplayPrice(): bool
    {
        return $this->showPrice;
    }

    public function setShowPrice(bool $showPrice): static
    {
        $this->showPrice = $showPrice;

        return $this;
    }

    public function setDisplayPrice(bool $displayPrice): static
    {
        return $this->setShowPrice($displayPrice);
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

    public function getDisplayTarget(): string
    {
        return match ($this->type) {
            FeaturedItemType::PRODUCT => $this->product?->getName() ?? 'Produit non defini',
            FeaturedItemType::CATEGORY => $this->category?->getName() ?? 'Categorie non definie',
            default => 'Mise en avant',
        };
    }

    public function getDisplayTitle(): string
    {
        $customTitle = trim((string) $this->customTitle);

        if ('' !== $customTitle) {
            return $customTitle;
        }

        return $this->getDisplayTarget();
    }

    public function getDisplayText(): ?string
    {
        $customText = trim((string) $this->shortText);

        if ('' !== $customText) {
            return $customText;
        }

        return match ($this->type) {
            FeaturedItemType::PRODUCT => $this->product?->getIngredients(),
            FeaturedItemType::CATEGORY => $this->category?->getDescription(),
            default => null,
        };
    }

    public function getDisplayImage(): ?string
    {
        return match ($this->type) {
            FeaturedItemType::PRODUCT => $this->product?->getImage(),
            FeaturedItemType::CATEGORY => $this->category?->getImage(),
            default => null,
        };
    }

    public function shouldDisplayPrice(): bool
    {
        return FeaturedItemType::PRODUCT === $this->type && $this->showPrice && null !== $this->product;
    }

    #[Assert\Callback]
    public function validateTargetConsistency(ExecutionContextInterface $context): void
    {
        if (null === $this->type) {
            return;
        }

        if (null !== $this->product && null !== $this->category) {
            $context->buildViolation('Une mise en avant doit cibler soit un produit, soit une categorie, jamais les deux.')
                ->atPath('product')
                ->addViolation();

            return;
        }

        if (FeaturedItemType::PRODUCT === $this->type) {
            if (null === $this->product) {
                $context->buildViolation('Selectionne un produit quand le type est "produit".')
                    ->atPath('product')
                    ->addViolation();
            }

            if (null !== $this->category) {
                $context->buildViolation('La categorie doit rester vide quand le type est "produit".')
                    ->atPath('category')
                    ->addViolation();
            }

            return;
        }

        if (null === $this->category) {
            $context->buildViolation('Selectionne une categorie quand le type est "categorie".')
                ->atPath('category')
                ->addViolation();
        }

        if (null !== $this->product) {
            $context->buildViolation('Le produit doit rester vide quand le type est "categorie".')
                ->atPath('product')
                ->addViolation();
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        if (FeaturedItemType::CATEGORY === $this->type) {
            $this->showPrice = false;
        }

        $this->updatedAt = new \DateTimeImmutable();
    }
}
