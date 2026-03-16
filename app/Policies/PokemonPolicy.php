<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pokemon;

/**
 * Policy responsible for handling authorization rules
 * related to Pokémon actions within the application.
 *
 * Defines which user roles are allowed to perform actions
 * such as importing Pokémon, managing favorites and deleting records.
 */
class PokemonPolicy
{
    /**
     * Determine whether the user can import Pokémon
     * from the PokéAPI into the local database.
     *
     * Only editors and administrators are allowed to perform
     * import operations.
     *
     * @param User $user
     * @return bool
     */
    public function import(User $user): bool
    {
        return $user->canEdit();
    }

    /**
     * Determine whether the user can mark Pokémon as favorites.
     *
     * This feature is available only to editors and administrators.
     *
     * @param User $user
     * @return bool
     */
    public function favorite(User $user): bool
    {
        return $user->canEdit();
    }

    /**
     * Determine whether the user can delete a specific Pokémon.
     *
     * Only administrators are allowed to delete Pokémon records
     * stored in the local database.
     *
     * The Pokémon model parameter is included to follow Laravel's
     * model policy conventions and allow future rule expansion.
     *
     * @param User $user
     * @param Pokemon $pokemon
     * @return bool
     */
    public function delete(User $user, Pokemon $pokemon): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete all imported Pokémon.
     *
     * This action clears the entire Pokémon dataset and therefore
     * is restricted to administrators.
     *
     * @param User $user
     * @return bool
     */
    public function deleteAll(User $user): bool
    {
        return $user->isAdmin();
    }
}