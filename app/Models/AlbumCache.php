<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Album Cache.
 *
 * @property bigInteger $id
 * @property string $discogs_release_id
 * @property string $album_name
 * @property string $image_url
 * @property int $length_in_seconds
 */
class AlbumCache extends Model
{
    use HasFactory;

    protected $table = 'albums_cache';

    protected $fillable = [
        'discogs_release_id',
        'album_name',
        'image_url',
        'length_in_seconds'
    ];

    public function albumCacheUsers(): HasMany
    {
        return $this->hasMany(AlbumCacheUser::class);
    }

    public function albumPlayUsers(): HasMany
    {
        return $this->hasMany(AlbumPlayUser::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public static function albumCacheFactory(
        string $discogsReleaseId,
        string $albumName,
        string $imageUrl,
        int $lengthInSeconds
    ): AlbumCache {
        $albumCache = new AlbumCache();
        $albumCache->discogs_release_id = $discogsReleaseId;
        $albumCache->album_name = $albumName;
        $albumCache->image_url = $imageUrl;
        $albumCache->length_in_seconds = $lengthInSeconds;

        return $albumCache;
    }
}
