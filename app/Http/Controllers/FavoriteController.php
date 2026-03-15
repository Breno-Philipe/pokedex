<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Services\FavoriteService;
use Illuminate\Http\Request;

/**
 * Controller responsible for managing pokemon favorites.
 *
 * Handles user interactions related to adding or removing
 * pokemons from their favorites list.
 */
class FavoriteController extends Controller
{
    public function __construct(
        private FavoriteService $favoriteService
    ) {}

    /**
     * Toggle a pokemon as favorite for the authenticated user.
     *
     * @param Request $request
     * @param Pokemon $pokemon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(Request $request, Pokemon $pokemon)
    {
        $this->favoriteService->toggle(
            $request->user(),
            $pokemon
        );

        return back();
    }
}