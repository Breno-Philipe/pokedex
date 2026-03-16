<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pokemon;
use App\Services\PokeApiClient;
use App\Services\PokemonDashboardService;
use App\Services\PokemonDetailsService;
use App\Services\PokemonImporter;
use App\Services\PokemonSearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controller responsible for handling Pokémon related interactions.
 *
 * Responsibilities:
 * - Display the Pokédex dashboard with locally imported Pokémon
 * - Display detailed Pokémon information
 * - Search Pokémon from the PokéAPI
 * - Import Pokémon into the local database
 * - Remove imported Pokémon
 *
 * Most business logic is delegated to dedicated services in order to
 * keep the controller focused on handling HTTP requests and responses.
 *
 * Services used:
 * - PokemonDashboardService
 * - PokemonSearchService
 * - PokemonImporter
 * - PokemonDetailsService
 */
class PokemonController extends Controller
{
    private const PAGE_SIZE = 18;

    public function __construct(
        private PokeApiClient $pokeApiClient,
        private PokemonImporter $pokemonImporter,
        private PokemonSearchService $pokemonSearchService,
        private PokemonDashboardService $pokemonDashboardService,
        private PokemonDetailsService $pokemonDetailsService
    ) {}

    /**
     * Display the main Pokédex dashboard.
     *
     * Retrieves locally imported Pokémon from the database using the
     * PokemonDashboardService and displays them paginated
     * with optional search filtering by name.
     *
     * Each Pokémon includes its associated types and a computed
     * attribute indicating whether it is favorited by the
     * authenticated user.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        $search = trim((string) $request->get('search'));

        $pokemons = $this->pokemonDashboardService
            ->getPokemons($user, self::PAGE_SIZE, $search);

        return view('pokemons.dashboard', [
            'pokemons' => $pokemons,
            'search' => $search
        ]);
    }

    /**
     * Display detailed information for a specific Pokémon.
     *
     * Loads Pokémon types from the local database and retrieves
     * additional information such as abilities and moves from
     * the PokéAPI through the PokemonDetailsService.
     *
     * @param Pokemon $pokemon
     * @return View
     */
    public function show(Pokemon $pokemon): View
    {
        $pokemon->load('types');

        $details = $this->pokemonDetailsService
            ->getDetails($pokemon);

        return view('pokemons.show', [
            'pokemon' => $pokemon,
            'abilities' => $details['abilities'],
            'moves' => $details['moves']
        ]);
    }

    /**
     * Display the Pokémon import interface.
     *
     * Uses the PokemonSearchService to retrieve Pokémon
     * from the PokéAPI with support for:
     * - pagination
     * - exact name search
     * - partial search fallback
     *
     * Also retrieves the list of already imported Pokémon
     * to disable the import button in the UI.
     *
     * @param Request $request
     * @return View
     */
    public function importPage(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $page = max((int) $request->get('page', 1), 1);

        $limit = self::PAGE_SIZE;
        $offset = ($page - 1) * $limit;

        $result = $this->pokemonSearchService->search($search, $limit, $offset);

        $imported = Pokemon::pluck('name')->toArray();

        return view('pokemons.import', [
            'pokemons' => $result['pokemons'],
            'count' => $result['count'],
            'page' => $page,
            'imported' => $imported,
            'search' => $search,
            'error' => $result['error']
        ]);
    }

    /**
     * Import a single Pokémon from the PokéAPI.
     *
     * Retrieves Pokémon data from the external API and
     * stores it locally using the PokemonImporter service.
     *
     * @param string $name
     * @return RedirectResponse
     */
    public function importOne(string $name): RedirectResponse
    {
        $data = $this->pokeApiClient->getPokemonByName($name);

        $this->pokemonImporter->import($data);

        return back()->with('success','Pokemon importado');
    }

    /**
     * Import multiple Pokémon from the current API page.
     *
     * Receives a list of Pokémon names from the request
     * and imports each one using the PokemonImporter service.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function importBatch(Request $request)
    {
        $names = $request->input('names', []);

        foreach ($names as $name) {

            $data = $this->pokeApiClient->getPokemonByName($name);

            $this->pokemonImporter->import($data);
        }

        return back()->with('success','Pokemons importados com sucesso.');
    }

    /**
     * Remove a specific imported Pokémon from the database.
     *
     * Authorization is handled through the PokemonPolicy
     * and only administrators are allowed to perform this action.
     *
     * @param Pokemon $pokemon
     * @return RedirectResponse
     */
    public function destroy(Pokemon $pokemon): RedirectResponse
    {
        $this->authorize('delete', $pokemon);

        $pokemon->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pokémon removido com sucesso.');
    }

    /**
     * Remove all imported Pokémon from the database.
     *
     * This action clears the entire Pokémon dataset and
     * is restricted to administrators via the PokemonPolicy.
     *
     * @return RedirectResponse
     */
    public function destroyAll(): RedirectResponse
    {
        $this->authorize('deleteAll', Pokemon::class);

        Pokemon::query()->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Todos os Pokémons foram removidos.');
    }
}