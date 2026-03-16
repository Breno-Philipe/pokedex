<?php

namespace App\Http\Controllers;

use App\Services\FavoritePokemonService;
use Illuminate\View\View;

/**
 * Controller responsible for displaying the authenticated user's
 * favorite Pokémon.
 *
 * This controller retrieves Pokémon that the current user has
 * marked as favorites and displays them in a paginated list.
 *
 * The retrieval logic is delegated to the FavoritePokemonService
 * to keep the controller focused on handling HTTP requests.
 */
class FavoritePokemonController extends Controller
{
    private const PAGE_SIZE = 18;

    public function __construct(
        private FavoritePokemonService $favoritePokemonService
    ) {}

    /**
     * Display the list of Pokémon favorited by the authenticated user.
     *
     * Authorization is handled through the PokemonPolicy and only
     * users with the appropriate role (editor or admin) are allowed
     * to access this feature.
     *
     * Pokémon are retrieved using the FavoritePokemonService and
     * returned paginated to the favorites view.
     *
     * @return View
     */
    public function index(): View
    {
        $this->authorize('favorite', Pokemon::class);

        $user = auth()->user();

        $pokemons = $this->favoritePokemonService
            ->getFavorites($user, self::PAGE_SIZE);

        return view('pokemons.favorites', compact('pokemons'));
    }
}