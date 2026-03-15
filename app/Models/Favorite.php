<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a user's favorite Pokémon entry.
 *
 * This model maps the relationship between a user and a locally imported Pokémon
 * marked as favorite.
 *
 * @property int $id
 * @property int $user_id
 * @property int $pokemon_id
 */
class Favorite extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'pokemon_id'
    ];

    /**
     * User who owns this favorite record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pokémon marked as favorite.
     */
    public function pokemon(): BelongsTo
    {
        return $this->belongsTo(Pokemon::class);
    }
}