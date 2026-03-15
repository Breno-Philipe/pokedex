<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/dashboard', [PokemonController::class,'index'])
        ->name('dashboard');

});

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::prefix('pokemons')->middleware('can:import,App\Models\Pokemon')->group(function () {

        Route::get('/import', [PokemonController::class,'importPage'])
            ->name('pokemons.import');

        Route::post('/import/{name}', [PokemonController::class,'importOne'])
            ->name('pokemons.import.one');

        Route::post('/import-batch', [PokemonController::class,'importBatch'])
            ->name('pokemons.import.batch');
    });

    Route::post('/favorites/{pokemon}', [FavoriteController::class,'toggle'])
        ->name('favorites.toggle');

});

require __DIR__.'/auth.php';