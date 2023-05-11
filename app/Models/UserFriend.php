<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * User Friend.
 *
 * @property bigInteger $id
 * @property bigInteger $user_id
 * @property bigInteger $friend_id
 */
class UserFriend extends Model
{
    use HasFactory;

    protected $table = 'user_styluses';

    protected $fillable = [
        'id',
        'user_id',
        'stylus_name',
        'is_retired',
    ];

    public function albumPlayUsers(): HasMany
    {
        return $this->hasMany(AlbumPlayUser::class);
    }
}
