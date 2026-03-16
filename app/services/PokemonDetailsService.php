<?php

namespace App\Services;

use App\Models\Pokemon;

/**
 * Service responsible for retrieving additional Pokémon details
 * from the external PokéAPI.
 *
 * This service complements the locally stored Pokémon data
 * by fetching extra information directly from the API.
 *
 * Responsibilities:
 * - Query the PokéAPI for detailed Pokémon data
 * - Extract relevant attributes for display
 * - Limit the amount of data returned to keep the UI clean
 *
 * The service currently retrieves:
 * - Pokémon abilities
 * - Pokémon moves
 */
class PokemonDetailsService
{
    private const MAX_ABILITIES = 4;
    private const MAX_MOVES = 6;

    public function __construct(
        private PokeApiClient $pokeApiClient
    ) {}

    /**
     * Retrieve additional details for a given Pokémon.
     *
     * Uses the Pokémon name to query the PokéAPI and
     * extracts a limited subset of the response for display.
     *
     * Extracted data:
     * - abilities (maximum of 4)
     * - moves (maximum of 6)
     *
     * @param Pokemon $pokemon Locally stored Pokémon
     *
     * @return array{
     *   abilities: \Illuminate\Support\Collection<int,string>,
     *   moves: \Illuminate\Support\Collection<int,string>
     * }
     */
    public function getDetails(Pokemon $pokemon): array
    {
        $data = $this->pokeApiClient->getPokemonByName($pokemon->name);

        return [
            'abilities' => collect($data['abilities'])
                ->pluck('ability.name')
                ->take(self::MAX_ABILITIES),

            'moves' => collect($data['moves'])
                ->pluck('move.name')
                ->take(self::MAX_MOVES),
        ];
    }
}