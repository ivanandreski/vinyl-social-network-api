<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * User Stylus.
 *
 * @property bigInteger $id
 * @property bigInteger $user_id
 * @property string $stylus_name
 * @property boolean $is_retired
 */
class UserStylus extends Model
{
    use HasFactory;

    protected $table = 'user_styluses';

    protected $fillable = [
        'id',
        'user_id',
        'stylus_name',
        'is_retired',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function albumPlayUsers(): HasMany
    {
        return $this->hasMany(AlbumPlayUser::class);
    }
}
