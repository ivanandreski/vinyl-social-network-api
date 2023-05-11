<?php

namespace App\Models\Enums;

enum UserProfileVisibilityEnum: string
{
    case PUBLIC = 'public';
    case FRIENDS_ONLY = 'friends_only';
    case PRIVATE = 'private';
}
