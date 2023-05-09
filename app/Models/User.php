<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User Stylus.
 *
 * @property bigInteger $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name'
    ];

    protected $hidden = [
        'password'
    ];

    public function albumCaches(): HasMany
    {
        return $this->hasMany(AlbumCacheUser::class);
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
}