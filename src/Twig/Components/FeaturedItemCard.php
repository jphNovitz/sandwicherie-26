<?php

namespace App\Twig\Components;

use App\Entity\FeaturedItem;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class FeaturedItemCard
{
    public FeaturedItem $featuredItem;
}
