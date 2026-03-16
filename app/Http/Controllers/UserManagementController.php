<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserManagementService;
use App\Http\Requests\UpdateUserRoleRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controller responsible for managing application users.
 *
 * This controller provides administrative features for:
 * - Listing registered users
 * - Updating user roles
 * - Deleting users from the system
 *
 * Access to these actions is restricted to users with the
 * "admin" role through authorization policies.
 */
class UserManagementController extends Controller
{
    public function __construct(
        private UserManagementService $userManagementService
    ) {}

    /**
     * Display the user management interface.
     *
     * Retrieves all registered users except the currently
     * authenticated one and displays them in the admin panel.
     *
     * Authorization:
     * Only users with the "admin" role can access this page.
     *
     * @return View
     */
    public function index(): View
    {
        $this->authorize('manageUsers', User::class);

        $users = $this->userManagementService->getUsers();

        return view('users.index', compact('users'));
    }

    /**
     * Update the role of a specific user.
     *
     * The request validation is handled by the
     * UpdateUserRoleRequest class.
     *
     * Allowed roles:
     * - viewer
     * - editor
     * - admin
     *
     * @param UpdateUserRoleRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        $this->userManagementService->updateRole($user, $request->role);

        return back()->with('success', 'Role atualizado com sucesso.');
    }

    /**
     * Delete a user from the system.
     *
     * This action permanently removes the user record
     * from the database.
     *
     * Authorization:
     * Only administrators can perform this operation.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('manageUsers', User::class);

        $this->userManagementService->deleteUser($user);

        return back()->with('success', 'Usuário removido.');
    }
}