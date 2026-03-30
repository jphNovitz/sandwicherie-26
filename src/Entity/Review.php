<?php

namespace App\Entity;

use App\Enum\ReviewSourceType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'review')]
#[ORM\HasLifecycleCallbacks]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?SiteSettings $siteSettings = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 5)]
    private ?string $lastInitial = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $content = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 5)]
    private int $rating = 5;

    #[ORM\Column(length: 20, enumType: ReviewSourceType::class)]
    private ?ReviewSourceType $sourceType = ReviewSourceType::DIRECT;

    #[ORM\Column(length: 500, nullable: true)]
    #[Assert\Url]
    private ?string $sourceUrl = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $sourceLabel = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    private int $position = 0;

    #[ORM\Column]
    private bool $isVisible = true;

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
        return $this->getDisplayAuthor();
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastInitial(): ?string
    {
        return $this->lastInitial;
    }

    public function setLastInitial(string $lastInitial): static
    {
        $this->lastInitial = strtoupper(trim($lastInitial));

        return $this;
    }

    public function getAuthorInitial(): ?string
    {
        return $this->getLastInitial();
    }

    public function setAuthorInitial(string $authorInitial): static
    {
        return $this->setLastInitial($authorInitial);
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->getContent();
    }

    public function setText(string $text): static
    {
        return $this->setContent($text);
    }

    public function getDisplayAuthor(): string
    {
        $firstName = trim((string) $this->firstName);
        $lastInitial = trim((string) $this->lastInitial);

        if ('' === $firstName && '' === $lastInitial) {
            return 'Avis client';
        }

        if ('' === $lastInitial) {
            return $firstName;
        }

        return trim(sprintf('%s %s.', $firstName, rtrim($lastInitial, '.')));
    }

    public function getSourceType(): ?ReviewSourceType
    {
        return $this->sourceType;
    }

    public function setSourceType(ReviewSourceType $sourceType): static
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    public function getSourceLabel(): ?string
    {
        return $this->sourceLabel;
    }

    public function setSourceLabel(?string $sourceLabel): static
    {
        $this->sourceLabel = $sourceLabel;

        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getDisplaySourceLabel(): string
    {
        $customLabel = trim((string) $this->sourceLabel);

        if ('' !== $customLabel) {
            return $customLabel;
        }

        return $this->sourceType?->getLabel() ?? 'Source';
    }

    public function getSource(): ?string
    {
        return $this->getDisplaySourceLabel();
    }

    public function setSource(?string $source): static
    {
        $normalizedSource = strtolower(trim((string) $source));

        $this->sourceType = match ($normalizedSource) {
            'google' => ReviewSourceType::GOOGLE,
            'facebook' => ReviewSourceType::FACEBOOK,
            'direct', 'avis direct', '' => ReviewSourceType::DIRECT,
            default => ReviewSourceType::OTHER,
        };

        $this->sourceLabel = match ($this->sourceType) {
            ReviewSourceType::GOOGLE,
            ReviewSourceType::FACEBOOK,
            ReviewSourceType::DIRECT => null,
            ReviewSourceType::OTHER => null !== $source ? trim($source) : null,
        };

        return $this;
    }

    public function getSourceUrl(): ?string
    {
        return $this->sourceUrl;
    }

    public function setSourceUrl(?string $sourceUrl): static
    {
        $this->sourceUrl = $sourceUrl;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->getSourceUrl();
    }

    public function setLink(?string $link): static
    {
        return $this->setSourceUrl($link);
    }

    public function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $now = new \DateTimeImmutable();
        $this->updatedAt = $now;

        if (!isset($this->createdAt)) {
            $this->createdAt = $now;
        }
    }

    public function normalizeAuthorInitial(): void
    {
        if (null !== $this->lastInitial) {
            $this->lastInitial = strtoupper(trim($this->lastInitial));
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function normalizeFields(): void
    {
        $this->normalizeAuthorInitial();
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

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): static
    {
        $this->isVisible = $isVisible;

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
