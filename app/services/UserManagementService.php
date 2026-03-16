<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserManagementService
{
    /**
     * Retrieve all users except the authenticated one.
     */
    public function getUsers(): Collection
    {
        return User::where('id', '!=', auth()->id())->get();
    }

    /**
     * Update user role.
     */
    public function updateRole(User $user, string $role): void
    {
        if ($user->id === auth()->id()) {return;}

        $user->update([
            'role' => $role
        ]);
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user): void
    {
        if ($user->id === auth()->id()) {return;}
        
        $user->delete();
    }
}