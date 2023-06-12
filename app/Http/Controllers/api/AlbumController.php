<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\AlbumCache;
use Illuminate\Http\Request;
use App\Models\AlbumCacheUser;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\Interface\AlbumCacheRepositoryInterface;

class AlbumController extends Controller
{
    private AlbumCacheRepositoryInterface $albumCacheRepository;

    public function __construct(AlbumCacheRepositoryInterface $albumCacheRepository)
    {
        $this->albumCacheRepository = $albumCacheRepository;
    }

    public function get(string $discogsId)
    {
        return $this->albumCacheRepository->findAlbumCacheByDiscogsId($discogsId);
    }

    public function getUserCollection(User $user)
    {
        $data = AlbumCacheUser::query()
            ->select('albums_cache.*')
            ->leftJoin('albums_cache', 'albums_cache.id', '=', 'album_cache_users.album_cache_id')
            ->where('album_cache_users.user_id', '=', $user->id)
            ->get();
        return response(['data' => $data], Response::HTTP_OK);
    }
}
