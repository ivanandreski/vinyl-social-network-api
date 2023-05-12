<?php

namespace App\Factory;

use App\Models\AlbumCache;
use App\Models\AlbumPlayUser;
use Illuminate\Support\Facades\Auth;

class AlbumFactory {
    public function createAlbumFromRequest(AlbumCache $albumCache, int $optionalLengthInMinutes = 40): AlbumPlayUser {
        // we need logic for styluses first!
        $userPlaySession = new AlbumPlayUser();
        $userPlaySession->user_id = Auth::user()->id;
        $userPlaySession->album_cache_id = $albumCache->id;
        // $userPlaySession->user_style_id

        return $userPlaySession;
    }
}
