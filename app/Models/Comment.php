<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Comment.
 *
 * @property bigInteger $id
 * @property string $body
 * @property bigInteger $post_id
 * @property bigInteger $user_id
 * @property bigInteger $parent_id
 */
class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'id',
        'body',
        'post_id',
        'user_id',
        'parent_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }
}
