<?php

namespace App\Enum;

enum PageCode: string
{
    case HOME = 'home';
    case MENU = 'menu';
    case ABOUT = 'about';
    case CONTACT = 'contact';
    case LEGAL = 'legal';
    case PRIVACY = 'privacy';
    case COOKIES = 'cookies';
}
