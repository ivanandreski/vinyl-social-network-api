<?php

namespace App\Models;

use App\Models\AlbumCacheUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Album Cache.
 *
 * @property bigInteger $id
 * @property string $discogs_id
 * @property string $discogs_resource_url
 * @property string $discogs_release_url
 * @property string $title
 * @property string $artist_name
 * @property string $image_url
 * @property int $length_in_seconds
 * @property string $column_hash
 */
class AlbumCache extends Model
{
    use HasFactory;

    protected $table = 'albums_cache';

    protected $fillable = [
        'discogs_id',
        'discogs_resource_url',
        'discogs_release_url',
        'title',
        'artist_name',
        'image_url',
        'length_in_seconds',
        'column_hash'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'column_hash',
        'pivot',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users');
    }

    public function albumPlayUsers(): HasMany
    {
        return $this->hasMany(AlbumPlayUser::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function updateColumns(AlbumCache $album): void {
        $this->title = $album->title;
        $this->artist_name = $album->artist_name;
        $this->discogs_release_url = $album->discogs_release_url;
        $this->discogs_resource_url = $album->discogs_resource_url;
        $this->length_in_seconds = $album->length_in_seconds;
        $this->image_url = $album->image_url;
    }

    public function generateColumnHash(): string {
        return hash(
            'sha256',
            $this->discogs_resource_url .
            $this->discogs_release_url .
            $this->title .
            $this->artist_name .
            $this->image_url .
            $this->length_in_seconds
        );
    }
}
