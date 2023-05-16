<?php

namespace App\Factory;

use App\Models\AlbumCache;

class AlbumFactory {
    public function createAlbumFromRequest(array $requestParams): AlbumCache {
        $album = new AlbumCache();
        $album->discogs_id = $requestParams['discogs_id'];
        $album->discogs_release_url = $requestParams['discogs_release_url'];
        $album->discogs_resource_url = $requestParams['discogs_resource_url'];
        $album->title = $requestParams['title'];
        $album->artist_name = $requestParams['artist_name'];
        $album->image_url = $requestParams['image_url'];
        $album->length_in_seconds = $requestParams['length_in_seconds'];
        $album->release_year = $requestParams['release_year'];
        $album->column_hash = $album->generateColumnHash();

        return $album;
    }
}
