<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserStylus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Album Play User.
 *
 * @property bigInteger $id
 * @property bigInteger $user_id
 * @property bigInteger $album_cache_id
 * @property bigInteger $user_style_id
 */
class AlbumPlayUser extends Model
{
    use HasFactory;

    protected $table = 'album_play_user';

    protected $fillable = [
        'user_id',
        'album_cache_id',
        'user_style_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function albumCache(): BelongsTo
    {
        return $this->belongsTo(AlbumCache::class);
    }

    public function userStylus(): BelongsTo
    {
        return $this->belongsTo(UserStylus::class);
    }
}
