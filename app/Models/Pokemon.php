<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents an imported Pokémon persisted locally.
 *
 * This model stores the core Pokémon data imported from the PokéAPI,
 * allowing the application to persist and relate Pokémon records with
 * types and user favorites.
 *
 * @property int $id
 * @property int $api_id
 * @property string $name
 * @property int $height
 * @property int $weight
 * @property string|null $sprite
 */
class Pokemon extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'api_id',
        'name',
        'height',
        'weight',
        'sprite'
    ];

    /**
     * Attribute casting definitions.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'api_id' => 'integer',
        'height' => 'integer',
        'weight' => 'integer',
    ];

    /**
     * Types associated with this Pokémon.
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class);
    }

    /**
     * Users who marked this Pokémon as favorite.
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}