<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Album Cache User.
 *
 * @property bigInteger $id
 * @property bigInteger $album_cache_id
 * @property bigInteger $user_id
 */
class AlbumCacheUser extends Model
{
    use HasFactory;

    protected $table = 'album_cache_users';

    protected $fillable = [
        'album_cache_id',
        'user_id',
    ];

    public function albumCache(): BelongsTo
    {
        return $this->belongsTo(AlbumCache::class, 'album_cache_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
