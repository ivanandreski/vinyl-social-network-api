<?php

namespace App\Http\Controllers\Api;

use App\Models\AlbumCache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AlbumPlayUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Factory\UserPlaySessionFactory;
use App\Repository\AlbumCacheRepository;
use App\Http\Requests\CreateUserPlaySessionRequest;
use App\Http\Requests\DeleteUserPlaySessionRequest;
use App\Repository\Interface\AlbumCacheRepositoryInterface;

class UserPlaySessionController extends Controller
{
    private AlbumCacheRepositoryInterface $albumCacheRepository;

    public function __construct(AlbumCacheRepositoryInterface $albumCacheRepository)
    {
        $this->albumCacheRepository = $albumCacheRepository;
    }

    public function create(CreateUserPlaySessionRequest $request)
    {
        $albumCache = $this->albumCacheRepository->findAlbumCacheByDiscogsId($request->discogs_id);
        $userPlaySessionFactory = new UserPlaySessionFactory();
        $userPlaySession = $userPlaySessionFactory->createAlbumFromRequest($albumCache, $request);
        $userPlaySession->save();

        return $userPlaySession;
    }

    public function delete()
    {
    }

    public function getForAlbumCache(Request $request) {
        $discogsId = $request->discogsId;
        $user = Auth::user();
        $album = $this->albumCacheRepository->findAlbumCacheByDiscogsId($discogsId);
        if($album == null) {
            return response(['data' => []], Response::HTTP_OK);
        }

        $data = AlbumPlayUser::where('user_id', $user->id)
            ->where('album_cache_id', $album->id)
            ->get();
        return response(['data' => $data], Response::HTTP_OK);
    }
}
