<?php

namespace App\Enum;

enum SocialLinkType: string
{
    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';
    case TIKTOK = 'tiktok';
    case OTHER = 'other';
}
