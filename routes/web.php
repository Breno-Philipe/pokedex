<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FavoritePokemonController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

/**
 * Root route.
 * Redirects users to login if not authenticated
 * or to the dashboard if already logged in.
 */
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/**
 * Dashboard
 * Displays the locally imported Pokémon list.
 */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [PokemonController::class, 'index'])
        ->name('dashboard');

});

/**
 * Pokémon details page
 */
Route::get('/pokemon/{pokemon}', [PokemonController::class, 'show'])
    ->name('pokemons.show')
    ->middleware('auth');

/**
 * Favorite Pokémon page
 */
Route::get('/favorites', [FavoritePokemonController::class, 'index'])
    ->name('pokemons.favorites')
    ->middleware(['auth', 'can:favorite,App\Models\Pokemon']);

/**
 * Routes available for authenticated users
 */
Route::middleware('auth')->group(function () {
    /**
     * Profile edit routes
     */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /**
     * Pokémon import routes
     * Only editors and admins can access.
     */
    Route::prefix('pokemons')
        ->middleware('can:import,App\Models\Pokemon')
        ->group(function () {

            Route::get('/import', [PokemonController::class, 'importPage'])
                ->name('pokemons.import');

            Route::post('/import/{name}', [PokemonController::class, 'importOne'])
                ->name('pokemons.import.one');

            Route::post('/import-batch', [PokemonController::class, 'importBatch'])
                ->name('pokemons.import.batch');

        });

    /**
     * Delete imported Pokémon (admin only)
     */
    Route::delete('/pokemons/{pokemon}', [PokemonController::class, 'destroy'])
        ->name('pokemons.destroy')
        ->middleware('can:delete,pokemon');

    Route::delete('/pokemons', [PokemonController::class, 'destroyAll'])
        ->name('pokemons.destroy.all')
        ->middleware('can:deleteAll,App\Models\Pokemon');

    // Toggle favorite
    Route::post('/favorites/{pokemon}', [FavoriteController::class, 'toggle'])
        ->name('favorites.toggle');
});

/**
 * Admin user management routes
 */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/users', [UserManagementController::class, 'index'])
        ->name('users.index')
        ->middleware('can:manageUsers,App\Models\User');

    Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])
        ->name('users.update.role')
        ->middleware('can:manageUsers,App\Models\User');

    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('can:manageUsers,App\Models\User');
});

require __DIR__.'/auth.php';