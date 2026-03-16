<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pokemon;

/**
 * Policy responsible for handling authorization rules
 * related to Pokémon actions within the application.
 *
 * This policy defines which user roles are allowed to perform
 * operations such as importing, deleting individual Pokémon,
 * or clearing the entire Pokémon dataset.
 */
class PokemonPolicy
{
    /**
     * Determine whether the user can import Pokémon
     * from the PokéAPI into the local database.
     *
     * Only users with the roles "editor" or "admin"
     * are authorized to perform import operations.
     *
     * @param User $user
     * @return bool
     */
    public function import(User $user): bool
    {
        return in_array($user->role, ['editor', 'admin'], true);
    }

    /**
     * Determine whether the user can favorite pokemons.
     *
     * Only editors and administrators are allowed to mark
     * pokemons as favorites.
     *
     * @param User $user
     * @return bool
     */
    public function favorite(User $user): bool
    {
        return in_array($user->role, ['editor','admin'], true);
    }

    /**
     * Determine whether the user can delete a specific Pokémon.
     *
     * This action is restricted to administrators.
     *
     * The Pokémon model is provided for compatibility with
     * Laravel's Model Policy conventions and future rules
     * that might depend on the specific Pokémon instance.
     *
     * @param User $user
     * @param Pokemon $pokemon
     * @return bool
     */
    public function delete(User $user, Pokemon $pokemon): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can delete all imported Pokémon.
     *
     * This operation allows administrators to remove all
     * Pokémon records stored in the database.
     *
     * @param User $user
     * @return bool
     */
    public function deleteAll(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Check if the user has administrator privileges.
     *
     * @param User $user
     * @return bool
     */
    private function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }
}