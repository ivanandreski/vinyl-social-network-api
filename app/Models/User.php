<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\UserFriend;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Enums\UserProfileVisibilityEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * User Stylus.
 *
 * @property bigInteger $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property UserProfileVisibilityEnum $visibility
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'visibility'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'visibility' => UserProfileVisibilityEnum::class
    ];

    public function albumCaches(): BelongsToMany
    {
        return $this->belongsToMany(AlbumCache::class, 'album_cache_users');
    }

    public function albumPlayUsers(): HasMany
    {
        return $this->hasMany(AlbumPlayUser::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function commentLikes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function postLikes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function userStyluses(): HasMany
    {
        return $this->hasMany(UserStylus::class);
    }

    public function friends(): HasMany {
        return $this->hasMany(UserFriend::class)->with('friend');
    }

    public function getFullName(): string {
        return $this->first_name . ' ' . $this->last_name;
    }
}
