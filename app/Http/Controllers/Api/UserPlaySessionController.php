<?php

namespace App\Http\Controllers\Api;

use App\Models\AlbumCache;
use Illuminate\Http\Request;
use App\Models\AlbumPlayUser;
use App\Http\Controllers\Controller;
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
}
