<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserPlaySessionRequest;
use App\Http\Requests\DeleteUserPlaySessionRequest;
use App\Models\AlbumCache;
use App\Models\AlbumPlayUser;
use App\Repository\AlbumCacheRepository;
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
        $userPlaySession = new AlbumPlayUser();
    }

    public function delete(DeleteUserPlaySessionRequest $request)
    {
    }
}
