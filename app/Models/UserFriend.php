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

    protected $table = 'user_friends';

    protected $fillable = [
        'id',
        'user_id',
        'friend_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
