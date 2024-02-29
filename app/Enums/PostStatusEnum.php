<?php

namespace App\Enums;

enum PostStatusEnum: string
{
    case APPROVED = 'approved';
    case DENIED = 'denied';
}
