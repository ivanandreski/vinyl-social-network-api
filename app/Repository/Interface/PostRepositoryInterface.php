<?php

namespace App\Repository\Interface;

use App\Http\Requests\GetPostsRequest;

interface PostRepositoryInterface {
    public function findAll(GetPostsRequest $request);
}
