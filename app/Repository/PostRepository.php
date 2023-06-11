<?php

namespace App\Repository;

use App\Models\Post;
use App\Models\Comment;
use App\Models\UserFriend;
use App\Models\Enums\SortTypeEnum;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\GetPostsRequest;
use App\Models\Enums\UserProfileVisibilityEnum;
use App\Repository\Interface\PostRepositoryInterface;

class PostRepository implements PostRepositoryInterface
{
    // TODO: add filter for friends only FOR NOW FRIENDS IDEA IS SCRAPED
    public function findAll(GetPostsRequest $request)
    {

        // TODO: put this in a helper method
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken());

        // Get the assigned user
        $user = $token?->tokenable;

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

        $posts = Post::select('posts.*')
            ->selectRaw('COUNT(post_likes.post_id) AS likes')
            ->selectRaw('MAX(CASE WHEN post_likes.user_id = ? THEN 1 ELSE 0 END) AS you_liked', [$user?->id ?? -1])
            ->leftJoin('post_likes', 'posts.id', '=', 'post_likes.post_id')
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')
            ->where('users.visibility', '!=', UserProfileVisibilityEnum::PRIVATE)
            ->with('user')
            ->with('albumCache')
            ->groupBy('posts.id')
            ->orderByDesc($sort, $direction)
            ->paginate(perPage: 1000000, page: $request->page);
        foreach ($posts as $post) {
            $post->comments = $this->findCommentsByPost($post);
            if ($user != null) {
                $post->user->is_follow = UserFriend::where('user_id', '=', $user->id)
                    ->where('friend_id', $post->user->id)
                    ->count() > 0;
            }
        }

        return $posts;
    }

    public function findCommentsByPost(Post $post)
    {
        $comments = Comment::where('post_id', $post->id)
            ->with('replies')
            ->with('user')
            ->whereNull('comment_id')
            ->get();

        $commentTree = [];

        foreach ($comments as $comment) {
            $commentTree[] = $this->buildCommentTree($comment);
        }

        return $commentTree;
    }

    public function buildCommentTree(Comment $comment)
    {
        $tree = [
            'id' => $comment->id,
            'body' => $comment->body,
            'created_at' => $comment->created_at,
            'user' => $comment->user,
            'replies' => [],
        ];

        foreach ($comment->replies as $reply) {
            $tree['replies'][] = $this->buildCommentTree($reply);
        }

        return $tree;
    }
}
