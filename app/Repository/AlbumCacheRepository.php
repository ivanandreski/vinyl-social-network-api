<?php

namespace App\Repository;

use App\Models\AlbumCache;
use App\Repository\Interface\AlbumCacheRepositoryInterface;

class AlbumCacheRepository implements AlbumCacheRepositoryInterface {
    public function findAlbumCacheByDiscogsId(string $discogsId): ?AlbumCache {
        return AlbumCache::where('discogs_id', $discogsId)->first();
    }
}
