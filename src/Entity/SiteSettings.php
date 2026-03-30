<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'site_settings')]
class SiteSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $businessName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slogan = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $whatsapp = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $fullAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortAddress = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 7, nullable: true)]
    private ?string $latitude = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 7, nullable: true)]
    private ?string $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $heroImage = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $notificationEmail = null;

    #[ORM\Column]
    private bool $emailNotificationsEnabled = true;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $generalNotes = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    /** @var Collection<int, OpeningHour> */
    #[ORM\OneToMany(mappedBy: 'siteSettings', targetEntity: OpeningHour::class)]
    private Collection $openingHours;

    /** @var Collection<int, SocialLink> */
    #[ORM\OneToMany(mappedBy: 'siteSettings', targetEntity: SocialLink::class)]
    private Collection $socialLinks;

    /** @var Collection<int, GalleryImage> */
    #[ORM\OneToMany(mappedBy: 'siteSettings', targetEntity: GalleryImage::class)]
    private Collection $galleryImages;

    /** @var Collection<int, Review> */
    #[ORM\OneToMany(mappedBy: 'siteSettings', targetEntity: Review::class)]
    private Collection $reviews;

    /** @var Collection<int, Page> */
    #[ORM\OneToMany(mappedBy: 'siteSettings', targetEntity: Page::class)]
    private Collection $pages;

    /** @var Collection<int, FeaturedItem> */
    #[ORM\OneToMany(mappedBy: 'siteSettings', targetEntity: FeaturedItem::class)]
    private Collection $featuredItems;

    /** @var Collection<int, AboutHighlight> */
    #[ORM\OneToMany(mappedBy: 'siteSettings', targetEntity: AboutHighlight::class)]
    private Collection $aboutHighlights;

    /** @var Collection<int, ContactMessage> */
    #[ORM\OneToMany(mappedBy: 'siteSettings', targetEntity: ContactMessage::class)]
    private Collection $contactMessages;

    public function __construct()
    {
        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->openingHours = new ArrayCollection();
        $this->socialLinks = new ArrayCollection();
        $this->galleryImages = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->pages = new ArrayCollection();
        $this->featuredItems = new ArrayCollection();
        $this->aboutHighlights = new ArrayCollection();
        $this->contactMessages = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->businessName ?? 'Configuration du site';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBusinessName(): ?string
    {
        return $this->businessName;
    }

    public function setBusinessName(string $businessName): static
    {
        $this->businessName = $businessName;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): static
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): static
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    public function getFullAddress(): ?string
    {
        return $this->fullAddress;
    }

    public function setFullAddress(?string $fullAddress): static
    {
        $this->fullAddress = $fullAddress;

        return $this;
    }

    public function getShortAddress(): ?string
    {
        return $this->shortAddress;
    }

    public function setShortAddress(?string $shortAddress): static
    {
        $this->shortAddress = $shortAddress;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function hasCoordinates(): bool
    {
        return null !== $this->getLatitudeAsFloat() && null !== $this->getLongitudeAsFloat();
    }

    public function getLatitudeAsFloat(): ?float
    {
        return is_numeric($this->latitude) ? (float) $this->latitude : null;
    }

    public function getLongitudeAsFloat(): ?float
    {
        return is_numeric($this->longitude) ? (float) $this->longitude : null;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getHeroImage(): ?string
    {
        return $this->heroImage;
    }

    public function setHeroImage(?string $heroImage): static
    {
        $this->heroImage = $heroImage;

        return $this;
    }

    public function getNotificationEmail(): ?string
    {
        return $this->notificationEmail;
    }

    public function setNotificationEmail(?string $notificationEmail): static
    {
        $this->notificationEmail = $notificationEmail;

        return $this;
    }

    public function isEmailNotificationsEnabled(): bool
    {
        return $this->emailNotificationsEnabled;
    }

    public function setEmailNotificationsEnabled(bool $emailNotificationsEnabled): static
    {
        $this->emailNotificationsEnabled = $emailNotificationsEnabled;

        return $this;
    }

    public function getGeneralNotes(): ?string
    {
        return $this->generalNotes;
    }

    public function setGeneralNotes(?string $generalNotes): static
    {
        $this->generalNotes = $generalNotes;

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

    /** @return Collection<int, OpeningHour> */
    public function getOpeningHours(): Collection
    {
        return $this->openingHours;
    }

    public function addOpeningHour(OpeningHour $openingHour): static
    {
        if (!$this->openingHours->contains($openingHour)) {
            $this->openingHours->add($openingHour);
            $openingHour->setSiteSettings($this);
        }

        return $this;
    }

    public function removeOpeningHour(OpeningHour $openingHour): static
    {
        if ($this->openingHours->removeElement($openingHour) && $openingHour->getSiteSettings() === $this) {
            $openingHour->setSiteSettings(null);
        }

        return $this;
    }

    /** @return Collection<int, SocialLink> */
    public function getSocialLinks(): Collection
    {
        return $this->socialLinks;
    }

    public function addSocialLink(SocialLink $socialLink): static
    {
        if (!$this->socialLinks->contains($socialLink)) {
            $this->socialLinks->add($socialLink);
            $socialLink->setSiteSettings($this);
        }

        return $this;
    }

    public function removeSocialLink(SocialLink $socialLink): static
    {
        if ($this->socialLinks->removeElement($socialLink) && $socialLink->getSiteSettings() === $this) {
            $socialLink->setSiteSettings(null);
        }

        return $this;
    }

    /** @return Collection<int, GalleryImage> */
    public function getGalleryImages(): Collection
    {
        return $this->galleryImages;
    }

    public function addGalleryImage(GalleryImage $galleryImage): static
    {
        if (!$this->galleryImages->contains($galleryImage)) {
            $this->galleryImages->add($galleryImage);
            $galleryImage->setSiteSettings($this);
        }

        return $this;
    }

    public function removeGalleryImage(GalleryImage $galleryImage): static
    {
        if ($this->galleryImages->removeElement($galleryImage) && $galleryImage->getSiteSettings() === $this) {
            $galleryImage->setSiteSettings(null);
        }

        return $this;
    }

    /** @return Collection<int, Review> */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setSiteSettings($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review) && $review->getSiteSettings() === $this) {
            $review->setSiteSettings(null);
        }

        return $this;
    }

    /** @return Collection<int, Page> */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Page $page): static
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
            $page->setSiteSettings($this);
        }

        return $this;
    }

    public function removePage(Page $page): static
    {
        if ($this->pages->removeElement($page) && $page->getSiteSettings() === $this) {
            $page->setSiteSettings(null);
        }

        return $this;
    }

    /** @return Collection<int, FeaturedItem> */
    public function getFeaturedItems(): Collection
    {
        return $this->featuredItems;
    }

    public function addFeaturedItem(FeaturedItem $featuredItem): static
    {
        if (!$this->featuredItems->contains($featuredItem)) {
            $this->featuredItems->add($featuredItem);
            $featuredItem->setSiteSettings($this);
        }

        return $this;
    }

    public function removeFeaturedItem(FeaturedItem $featuredItem): static
    {
        if ($this->featuredItems->removeElement($featuredItem) && $featuredItem->getSiteSettings() === $this) {
            $featuredItem->setSiteSettings(null);
        }

        return $this;
    }

    /** @return Collection<int, AboutHighlight> */
    public function getAboutHighlights(): Collection
    {
        return $this->aboutHighlights;
    }

    public function addAboutHighlight(AboutHighlight $aboutHighlight): static
    {
        if (!$this->aboutHighlights->contains($aboutHighlight)) {
            $this->aboutHighlights->add($aboutHighlight);
            $aboutHighlight->setSiteSettings($this);
        }

        return $this;
    }

    public function removeAboutHighlight(AboutHighlight $aboutHighlight): static
    {
        if ($this->aboutHighlights->removeElement($aboutHighlight) && $aboutHighlight->getSiteSettings() === $this) {
            $aboutHighlight->setSiteSettings(null);
        }

        return $this;
    }

    /** @return Collection<int, ContactMessage> */
    public function getContactMessages(): Collection
    {
        return $this->contactMessages;
    }

    public function addContactMessage(ContactMessage $contactMessage): static
    {
        if (!$this->contactMessages->contains($contactMessage)) {
            $this->contactMessages->add($contactMessage);
            $contactMessage->setSiteSettings($this);
        }

        return $this;
    }

    public function removeContactMessage(ContactMessage $contactMessage): static
    {
        if ($this->contactMessages->removeElement($contactMessage) && $contactMessage->getSiteSettings() === $this) {
            $contactMessage->setSiteSettings(null);
        }

        return $this;
    }
}
