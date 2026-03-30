<?php

namespace App\Enum;

enum ReviewSourceType: string
{
    case GOOGLE = 'google';
    case FACEBOOK = 'facebook';
    case DIRECT = 'direct';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::GOOGLE => 'Google',
            self::FACEBOOK => 'Facebook',
            self::DIRECT => 'Avis direct',
            self::OTHER => 'Autre',
        };
    }
}
