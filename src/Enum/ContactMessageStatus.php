<?php

namespace App\Enum;

enum ContactMessageStatus: string
{
    case NEW = 'new';
    case READ = 'read';
    case PROCESSED = 'processed';
}
