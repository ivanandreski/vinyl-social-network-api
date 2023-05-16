<?php

namespace App\Models\Enums;

enum SortTypeEnum: string
{
    case NEWEST = 'newest';
    case OLDEST = 'oldest';
    case MOST_POPULAR = 'most_popular';

    public static function getAllSortTypes()
    {
        return [
            SortTypeEnum::NEWEST->value,
            SortTypeEnum::OLDEST->value,
            SortTypeEnum::MOST_POPULAR->value,
        ];
    }
}
