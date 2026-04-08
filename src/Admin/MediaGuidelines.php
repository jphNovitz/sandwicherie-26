<?php

namespace App\Admin;

use Symfony\Component\Validator\Constraints\Image;

final class MediaGuidelines
{
    /**
     * @return array<int, Image>
     */
    public static function logoConstraints(): array
    {
        return [self::buildImageConstraint('300k', 240, 96)];
    }

    public static function logoHelp(): string
    {
        return 'Formats acceptes : JPG, PNG, WEBP. Recommande : 400 x 160 px. Minimum conseille : 240 x 96 px. Poids cible : 200 KB, maximum recommande : 300 KB.';
    }

    /**
     * @return array<int, Image>
     */
    public static function heroConstraints(): array
    {
        return [self::buildImageConstraint('800k', 1280, 720)];
    }

    public static function heroHelp(): string
    {
        return 'Formats acceptes : JPG, PNG, WEBP. Ratio recommande : 16:9. Recommande : 1600 x 900 px. Minimum conseille : 1280 x 720 px. Poids cible : 500 KB, maximum recommande : 800 KB.';
    }

    /**
     * @return array<int, Image>
     */
    public static function categoryConstraints(): array
    {
        return [self::buildImageConstraint('450k', 800, 600)];
    }

    public static function categoryHelp(): string
    {
        return 'Formats acceptes : JPG, PNG, WEBP. Ratio recommande : 4:3. Recommande : 960 x 720 px. Minimum conseille : 800 x 600 px. Poids cible : 300 KB, maximum recommande : 450 KB.';
    }

    /**
     * @return array<int, Image>
     */
    public static function productConstraints(): array
    {
        return [self::buildImageConstraint('400k', 640, 480)];
    }

    public static function productHelp(): string
    {
        return 'Formats acceptes : JPG, PNG, WEBP. Ratio recommande : 4:3. Recommande : 800 x 600 px. Minimum conseille : 640 x 480 px. Poids cible : 250 KB, maximum recommande : 400 KB.';
    }

    private static function buildImageConstraint(string $maxSize, int $minWidth, int $minHeight): Image
    {
        return new Image([
            'maxSize' => $maxSize,
            'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
            'mimeTypesMessage' => 'Formats acceptes : JPG, PNG ou WEBP.',
            'minWidth' => $minWidth,
            'minWidthMessage' => sprintf('Image trop petite : largeur minimale recommandee %d px.', $minWidth),
            'minHeight' => $minHeight,
            'minHeightMessage' => sprintf('Image trop petite : hauteur minimale recommandee %d px.', $minHeight),
            'maxSizeMessage' => 'Fichier trop lourd pour la V1.',
        ]);
    }
}
