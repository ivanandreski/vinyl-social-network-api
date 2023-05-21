<?php

namespace App\Repository\Interface;

use App\Models\Post;
use App\Models\Comment;
use App\Http\Requests\GetPostsRequest;

interface PostRepositoryInterface {
    public function findAll(GetPostsRequest $request);

    public function findCommentsByPost(Post $post);

    public function buildCommentTree(Comment $comment);
}
