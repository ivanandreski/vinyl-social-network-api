<?php

namespace App\Repository\Interface;

use App\Models\AlbumCache;

interface AlbumCacheRepositoryInterface {
    public function findAlbumCacheByDiscogsId(string $discogsId): ?AlbumCache;
}