<?php

namespace App\Factory;

use App\Http\Requests\CreateUserPlaySessionRequest;
use App\Models\AlbumCache;
use App\Models\AlbumPlayUser;
use App\Models\UserStylus;
use Illuminate\Support\Facades\Auth;

class UserPlaySessionFactory {
    public function createAlbumFromRequest(AlbumCache $albumCache, CreateUserPlaySessionRequest $request): AlbumPlayUser {
        $userPlaySession = new AlbumPlayUser();
        $userPlaySession->user_id = Auth::user()->id;
        $userPlaySession->album_cache_id = $albumCache->id;
        $userPlaySession->user_stylus_id = $request->stylus_id;

        return $userPlaySession;
    }
}
