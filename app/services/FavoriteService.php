<?php

namespace App\Services;

use App\Models\User;
use App\Models\Pokemon;

/**
 * Service responsible for managing user favorite pokemons.
 *
 * This service encapsulates the logic for toggling favorite
 * relationships between users and imported pokemons.
 */
class FavoriteService
{
    /**
     * Toggle a pokemon as favorite for the given user.
     *
     * If the pokemon is already favorited it will be removed,
     * otherwise it will be added.
     *
     * @param User $user
     * @param Pokemon $pokemon
     * @return void
     */
    public function toggle(User $user, Pokemon $pokemon): void
    {
        $user->favorites()->toggle($pokemon->id);
    }
}