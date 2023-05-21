<?php

namespace App\Http\Controllers\api;

use App\Models\AlbumCache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\Interface\AlbumCacheRepositoryInterface;

class AlbumController extends Controller
{
    private AlbumCacheRepositoryInterface $albumCacheRepository;

    public function __construct(AlbumCacheRepositoryInterface $albumCacheRepository)
    {
        $this->albumCacheRepository = $albumCacheRepository;
    }

    public function get(string $discogsId) {
        return $this->albumCacheRepository->findAlbumCacheByDiscogsId($discogsId);
    }
}
