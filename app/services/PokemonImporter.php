<?php

namespace App\Services;

use App\Models\Pokemon;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

/**
 * Service responsible for importing Pokémon data retrieved
 * from the PokéAPI and persisting it into the local database.
 *
 * This service ensures data consistency by:
 * - avoiding duplicated Pokémon using updateOrCreate
 * - creating missing Pokémon types if they do not exist
 * - synchronizing the Pokémon ↔ Types relationship
 * - executing the operation inside a database transaction
 *
 * The service expects a payload compatible with the structure
 * returned by the PokéAPI endpoint:
 * https://pokeapi.co/api/v2/pokemon/{name}
 */
class PokemonImporter
{
    /**
     * Import a Pokémon using the PokéAPI response payload.
     *
     * Expected payload structure (simplified):
     *
     * [
     *   'id' => int,
     *   'name' => string,
     *   'height' => int,
     *   'weight' => int,
     *   'sprites' => [
     *       'other' => [
     *           'official-artwork' => [
     *               'front_default' => string|null
     *           ]
     *       ]
     *   ],
     *   'types' => [
     *       [
     *           'type' => [
     *               'name' => string
     *           ]
     *       ]
     *   ]
     * ]
     *
     * The operation is executed within a database transaction
     * to ensure that Pokémon and its related types are persisted
     * atomically.
     *
     * @param array<string,mixed> $data PokéAPI response payload
     *
     * @return Pokemon
     */
    public function import(array $data): Pokemon
    {
        return DB::transaction(function () use ($data) {

            /**
             * Create or update the Pokémon using the API id
             * to avoid duplication.
             */
            $sprite = $data['sprites']['other']['official-artwork']['front_default'] ?? null;
            $pokemon = Pokemon::updateOrCreate(
                ['api_id' => $data['id']],
                [
                    'name' => $data['name'],
                    'height' => $data['height'],
                    'weight' => $data['weight'],
                    'sprite' => $sprite
                ]
            );

            $types = [];

            /**
             * Persist types if they do not exist and collect their ids.
             */
            foreach ($data['types'] as $typeData) {

                $type = Type::firstOrCreate([
                    'name' => $typeData['type']['name']
                ]);

                $types[] = $type->id;
            }

            /**
             * Sync Pokémon ↔ Types relationship.
             */
            $pokemon->types()->sync($types);

            return $pokemon;
        });
    }
}