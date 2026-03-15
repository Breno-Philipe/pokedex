<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use App\Services\PokeApiClient;
use App\Services\PokemonImporter;
use Illuminate\Http\Request;

/**
 * Toggle a pokemon as favorite for the authenticated user.
 *
 * @param Request $request
 * @param Pokemon $pokemon
 * @return \Illuminate\Http\RedirectResponse
 */
class PokemonController extends Controller
{
    public function __construct(
        private PokeApiClient $pokeApiClient,
        private PokemonImporter $pokemonImporter
    ) {}

    /**
     * Toggle a pokemon as favorite for the authenticated user.
     *
     * @param Request $request
     * @param Pokemon $pokemon
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $pokemons = Pokemon::with('types')->paginate(20);

        return view('pokemons.dashboard', compact('pokemons'));
    }

    /**
     * Display paginated pokemon list from the PokéAPI.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function importPage(Request $request)
    {
        $page = $request->get('page',1);

        $limit = 20;
        $offset = ($page - 1) * $limit;

        $response = $this->pokeApiClient->listPokemons($limit,$offset);

        return view('pokemons.import',[
            'pokemons' => $response['results'],
            'count' => $response['count'],
            'page' => $page
        ]);
    }

    /**
     * Import a single pokemon from the PokéAPI.
     *
     * @param string $name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importOne(string $name)
    {
        $data = $this->pokeApiClient->getPokemonByName($name);

        $this->pokemonImporter->import($data);

        return back()->with('success','Pokemon importado');
    }

    /**
     * Import multiple pokemons from the current API page.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importBatch(Request $request)
    {
        $names = $request->input('names');

        foreach ($names as $name) {

            $data = $this->pokeApiClient->getPokemonByName($name);

            $this->pokemonImporter->import($data);
        }

        return back()->with('success','Pokemons importados');
    }
}