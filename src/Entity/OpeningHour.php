<?php

namespace App\Entity;

use App\Enum\DayOfWeek;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'opening_hour')]
class OpeningHour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'openingHours')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?SiteSettings $siteSettings = null;

    #[ORM\Column(length: 20, enumType: DayOfWeek::class)]
    private ?DayOfWeek $dayOfWeek = null;

    #[ORM\Column]
    private bool $isClosed = false;

    #[ORM\Column(type: 'time_immutable', nullable: true)]
    private ?\DateTimeImmutable $firstOpeningAt = null;

    #[ORM\Column(type: 'time_immutable', nullable: true)]
    private ?\DateTimeImmutable $firstClosingAt = null;

    #[ORM\Column(type: 'time_immutable', nullable: true)]
    private ?\DateTimeImmutable $secondOpeningAt = null;

    #[ORM\Column(type: 'time_immutable', nullable: true)]
    private ?\DateTimeImmutable $secondClosingAt = null;

    #[ORM\Column]
    private int $position = 0;

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

    public function getDayOfWeek(): ?DayOfWeek
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(DayOfWeek $dayOfWeek): static
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function isClosed(): bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): static
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    public function getFirstOpeningAt(): ?\DateTimeImmutable
    {
        return $this->firstOpeningAt;
    }

    public function setFirstOpeningAt(?\DateTimeImmutable $firstOpeningAt): static
    {
        $this->firstOpeningAt = $firstOpeningAt;

        return $this;
    }

    public function getFirstClosingAt(): ?\DateTimeImmutable
    {
        return $this->firstClosingAt;
    }

    public function setFirstClosingAt(?\DateTimeImmutable $firstClosingAt): static
    {
        $this->firstClosingAt = $firstClosingAt;

        return $this;
    }

    public function getSecondOpeningAt(): ?\DateTimeImmutable
    {
        return $this->secondOpeningAt;
    }

    public function setSecondOpeningAt(?\DateTimeImmutable $secondOpeningAt): static
    {
        $this->secondOpeningAt = $secondOpeningAt;

        return $this;
    }

    public function getSecondClosingAt(): ?\DateTimeImmutable
    {
        return $this->secondClosingAt;
    }

    public function setSecondClosingAt(?\DateTimeImmutable $secondClosingAt): static
    {
        $this->secondClosingAt = $secondClosingAt;

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
}
