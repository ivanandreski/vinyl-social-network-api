<?php

namespace App\Repository;

use App\Models\Post;
use App\Models\Enums\SortTypeEnum;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\GetPostsRequest;
use App\Models\Enums\UserProfileVisibilityEnum;
use App\Repository\Interface\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    // TODO: add filter for friends only
    public function findAll(GetPostsRequest $request)
    {
        $user = Auth::user();

        $sort = 'created_at';
        $direction = 'desc';
        switch ($request->sort) {
            case SortTypeEnum::OLDEST:
                $direction = 'asc';
                break;
            case SortTypeEnum::MOST_POPULAR:
                $sort = 'likes';
                break;
        }

        return Post::select('posts.*')
            ->selectRaw('COUNT(post_likes.post_id) AS likes')
            ->selectRaw('MAX(CASE WHEN post_likes.user_id = ? THEN 1 ELSE 0 END) AS you_liked', [$user?->id ?? -1])
            ->leftJoin('post_likes', 'posts.id', '=', 'post_likes.post_id')
            ->leftJoin('users', 'user.id', '=', 'posts.user_id')
            ->where('users.visibility', '!=', UserProfileVisibilityEnum::PRIVATE)
            ->groupBy('posts.id')
            ->orderByDesc($sort, $direction)
            ->paginate(perPage: 10, page: $request->page);
    }
}
