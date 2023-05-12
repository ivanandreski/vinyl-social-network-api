<?php

namespace App\Models\Enums;

enum UserProfileVisibilityEnum: string
{
    case PUBLIC = 'public';
    case FRIENDS_ONLY = 'friends_only';
    case PRIVATE = 'private';

    public static function getAllVisibilities()
    {
        return [
            UserProfileVisibilityEnum::PUBLIC->value,
            UserProfileVisibilityEnum::FRIENDS_ONLY->value,
            UserProfileVisibilityEnum::PRIVATE->value,
        ];
    }
}
