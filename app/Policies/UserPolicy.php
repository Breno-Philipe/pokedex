<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy responsible for handling authorization rules
 * related to user management within the application.
 *
 * Only administrators are allowed to manage users.
 */
class UserPolicy
{
    /**
     * Determine whether the authenticated user can manage users.
     *
     * This permission allows access to the user management
     * interface and actions such as updating roles or deleting users.
     *
     * @param User $user
     * @return bool
     */
    public function manageUsers(User $user): bool
    {
        return $user->isAdmin();
    }
}