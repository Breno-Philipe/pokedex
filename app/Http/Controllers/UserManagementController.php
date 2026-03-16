<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserManagementController extends Controller
{
    public function __construct(
        private UserManagementService $userManagementService
    ) {}

    /**
     * Display the user management page.
     *
     * Lists all users except the currently authenticated one.
     */
    public function index(): View
    {
        $this->authorize('manageUsers', User::class);

        $users = $this->userManagementService->getUsers();

        return view('users.index', compact('users'));
    }

    /**
     * Update the role of a user.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $this->authorize('manageUsers', User::class);

        $request->validate([
            'role' => 'required|in:viewer,editor,admin'
        ]);

        $this->userManagementService->updateRole($user, $request->role);

        return back()->with('success', 'Role atualizado.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('manageUsers', User::class);

        $this->userManagementService->deleteUser($user);

        return back()->with('success', 'Usuário removido.');
    }
}