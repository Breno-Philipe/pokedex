<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents a Pokémon type persisted locally.
 *
 * Examples: fire, water, grass, electric.
 *
 * @property int $id
 * @property string $name
*/
class Type extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'name'
    ];

    /**
     * Pokémons associated with this type.
    */
    public function pokemons(): BelongsToMany
    {
        return $this->belongsToMany(Pokemon::class);
    }
}