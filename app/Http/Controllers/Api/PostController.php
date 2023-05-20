<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\GetPostsRequest;
use App\Http\Requests\CreatePostRequest;
use Illuminate\Http\Request;
use App\Models\PostLike;
use App\Repository\Interface\AlbumCacheRepositoryInterface;
use App\Repository\Interface\PostRepositoryInterface;
use Brick\Math\BigInteger;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    private AlbumCacheRepositoryInterface $albumCacheRepository;
    private PostRepositoryInterface $postRepository;

    public function __construct(
        AlbumCacheRepositoryInterface $albumCacheRepository,
        PostRepositoryInterface $postRepository,
    ) {
        $this->albumCacheRepository = $albumCacheRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetPostsRequest $request)
    {
        return $this->postRepository->findAll($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(CreatePostRequest $request): Post
    {
        $user = Auth::user();
        $post = new Post();
        $post->user_id = $user->id;
        $post->album_cache_id = $this->albumCacheRepository->findAlbumCacheByDiscogsId($request->discogs_id)->id;
        $post->text = $request->text ?? '';
        $post->save();

        return $post;
    }

    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        $postLike = PostLike::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($postLike == null) {
            $postLike = new PostLike();
            $postLike->post_id = $post->id;
            $postLike->user_id = $user->id;
            $postLike->save();
        } else {
            $postLike->delete();
        }

        return response(['likes' => $post->likes()->count()], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request): Post
    {
        // TODO: put this in a helper method
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken());

        // Get the assigned user
        $user = $token?->tokenable;

        return Post::select('posts.*')
            ->selectRaw('COUNT(post_likes.post_id) AS likes')
            ->selectRaw('MAX(CASE WHEN post_likes.user_id = ? THEN 1 ELSE 0 END) AS you_liked', [$user?->id ?? -1])
            ->leftJoin('post_likes', 'posts.id', '=', 'post_likes.post_id')
            ->where('posts.id', $id)
            ->with('albumCache')
            ->groupBy('posts.id')
            ->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Post $post)
    {
        $user = Auth::user();
        if ($post->user_id != $user->id) {
            return response(['message' => 'You do not have permission to delete this post'], Response::HTTP_UNAUTHORIZED);
        }

        $post->delete();
        return response(['message' => 'Success'], Response::HTTP_OK);
    }
}
