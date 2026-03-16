<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service responsible for handling user management operations.
 *
 * This service centralizes the business logic related to
 * administrative actions over application users, such as:
 *
 * - retrieving the list of users
 * - updating user roles
 * - deleting users
 *
 * Security rules applied:
 * - the authenticated user cannot modify their own role
 * - the authenticated user cannot delete themselves
 */
class UserManagementService
{
    /**
     * Retrieve all users except the authenticated one.
     *
     * This method is used by the user management interface
     * to display all manageable users while preventing
     * the logged-in user from managing themselves.
     *
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return User::where('id', '!=', auth()->id())->get();
    }
    /**
     * Update the role of a given user.
     *
     * The authenticated user is prevented from updating
     * their own role to avoid privilege escalation issues.
     *
     * @param User $user
     * @param string $role
     * @return void
     */
    public function updateRole(User $user, string $role): void
    {
        if ($user->id === auth()->id()) {return;}

        $user->update([
            'role' => $role
        ]);
    }

    /**
     * Delete a user from the system.
     *
     * The authenticated user cannot delete themselves
     * to prevent accidental account removal.
     *
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user): void
    {
        if ($user->id === auth()->id()) {return;}
        
        $user->delete();
    }
}