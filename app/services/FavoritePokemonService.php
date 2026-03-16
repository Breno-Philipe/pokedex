<?php

namespace App\Services;

use App\Models\User;
use App\Models\Pokemon;

class FavoritePokemonService
{
    /**
     * Retrieve the authenticated user's favorite pokemons.
     *
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFavorites(User $user, int $perPage)
    {
        return Pokemon::with('types')
            ->whereHas('favoritedBy', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->paginate($perPage);
    }
}