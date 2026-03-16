<?php

namespace App\Services;

use App\Models\Pokemon;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Service responsible for retrieving Pokémon displayed
 * in the Pokédex dashboard.
 *
 * Retrieves locally stored Pokémon and determines whether
 * each Pokémon is marked as favorite by the authenticated user.
 */
class PokemonDashboardService
{
    /**
     * Retrieve paginated Pokémon for the dashboard.
     *
     * Each Pokémon includes the computed attribute "is_favorite"
     * indicating whether the current user has favorited it.
     *
     * @param User $user
     * @param int $perPage
     * @return LengthAwarePaginator<Pokemon>
     */
    public function getPokemons(User $user, int $perPage): LengthAwarePaginator
    {
        return Pokemon::with('types')
            ->withCount([
                'favoritedBy as is_favorite' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            ])
            ->paginate($perPage);
    }
}