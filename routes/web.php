<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FavoritePokemonController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [PokemonController::class, 'index'])
        ->name('dashboard');

});

/* Pokemon details Page */
Route::get('/pokemon/{pokemon}', [PokemonController::class, 'show'])
    ->name('pokemons.show')
    ->middleware('auth');

/* Favorite Pokemon Page */
Route::get('/favorites', [FavoritePokemonController::class, 'index'])
    ->name('pokemons.favorites')
    ->middleware(['auth', 'can:favorite,App\Models\Pokemon']);

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /* Pokemon Import Routes */
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

    /* Pokemon Delete Routes (Admin only) */
    Route::delete('/pokemons/{pokemon}', [PokemonController::class, 'destroy'])
        ->name('pokemons.destroy')
        ->middleware('can:delete,pokemon');

    Route::delete('/pokemons', [PokemonController::class, 'destroyAll'])
        ->name('pokemons.destroy.all')
        ->middleware('can:deleteAll,App\Models\Pokemon');

    /* Favorites */
    Route::post('/favorites/{pokemon}', [FavoriteController::class, 'toggle'])
        ->name('favorites.toggle');
});

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
