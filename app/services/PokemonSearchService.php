<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Service responsible for searching Pokémon using the PokéAPI.
 *
 * This service centralizes all search logic related to Pokémon retrieval
 * from the external API, keeping controllers thin and focused only on
 * handling HTTP requests and responses.
 *
 * Responsibilities:
 * - Perform paginated listing of Pokémon from the PokéAPI
 * - Handle exact search by Pokémon name
 * - Provide partial search fallback when exact match fails
 * - Normalize the response structure returned to the controller
 *
 * Returned structure:
 *
 * [
 *   'pokemons' => array<int, array{name:string,url:string}>,
 *   'count' => int,
 *   'error' => string|null
 * ]
 *
 * This ensures a consistent contract for the controller regardless of
 * which search strategy was used.
 */
class PokemonSearchService
{
    private const SEARCH_POOL_SIZE = 1000;
    private const SEARCH_LIMIT = 18;

    public function __construct(
        private PokeApiClient $pokeApiClient
    ) {}

    /**
     * Search Pokémon by name or return a paginated list.
     *
     * If a valid search term is provided (minimum 3 characters),
     * the service attempts an exact match search. If no exact match
     * is found, it falls back to a partial search against the API list.
     *
     * If no search term is provided, a paginated list is returned.
     *
     * @param string|null $search Search query
     * @param int $limit Number of results per page
     * @param int $offset Pagination offset
     *
     * @return array{
     *   pokemons: array<int, array{name:string,url:string}>,
     *   count: int,
     *   error: string|null
     * }
     */
    public function search(?string $search, int $limit, int $offset): array
    {
        $search = trim((string) $search);

        if ($search !== '' && strlen($search) >= 3) {
            return $this->searchByName($search);
        }

        return $this->list($limit, $offset);
    }

    /**
     * Retrieve a paginated list of Pokémon from the PokéAPI.
     *
     * @param int $limit Number of results per page
     * @param int $offset Pagination offset
     *
     * @return array{
     *   pokemons: array<int, array{name:string,url:string}>,
     *   count: int,
     *   error: string|null
     * }
     */
    private function list(int $limit, int $offset): array
    {
        $response = $this->pokeApiClient->listPokemons($limit, $offset);

        return [
            'pokemons' => $response['results'],
            'count' => $response['count'],
            'error' => null
        ];
    }

    /**
     * Attempt to search Pokémon by exact name.
     *
     * If the exact match fails, a fallback partial search is performed by
     * retrieving a larger pool of Pokémon from the API and filtering
     * results locally based on the search term.
     *
     * @param string $search Pokémon name or partial name
     *
     * @return array{
     *   pokemons: array<int, array{name:string,url:string}>,
     *   count: int,
     *   error: string|null
     * }
     */
    private function searchByName(string $search): array
    {
        try {

            $pokemon = $this->pokeApiClient->getPokemonByName($search);

            return [
                'pokemons' => [[
                    'name' => $pokemon['name'],
                    'url' => "https://pokeapi.co/api/v2/pokemon/{$pokemon['id']}/"
                ]],
                'count' => 1,
                'error' => null
            ];

        } catch (\RuntimeException $e) {

            $response = $this->pokeApiClient->listPokemons(self::SEARCH_POOL_SIZE, 0);

            /** @var Collection<int, array{name:string,url:string}> $filtered */
            $filtered = collect($response['results'])
                ->filter(fn ($pokemon) =>
                    str_contains($pokemon['name'], strtolower($search))
                )
                ->values()
                ->take(self::SEARCH_LIMIT);

            if ($filtered->isEmpty()) {

                return [
                    'pokemons' => [],
                    'count' => 0,
                    'error' => 'Nenhum Pokémon encontrado.'
                ];
            }

            return [
                'pokemons' => $filtered->toArray(),
                'count' => $filtered->count(),
                'error' => null
            ];
        }
    }
}