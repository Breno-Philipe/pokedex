<?php

namespace App\Services;

use App\Models\Pokemon;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

/**
 * Service responsible for importing Pokémon data from the PokéAPI
 * and persisting it in the local database.
 *
 * This service ensures data consistency by:
 * - avoiding duplication using updateOrCreate
 * - creating missing types
 * - syncing many-to-many relationships
 * - wrapping the operation inside a database transaction
 */
class PokemonImporter
{
    /**
     * Import a Pokémon from the PokéAPI payload.
     *
     * Expected payload structure:
     *
     * [
     *   'id' => int,
     *   'name' => string,
     *   'height' => int,
     *   'weight' => int,
     *   'sprites' => [
     *       'front_default' => string|null
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
     * @param array<string, mixed> $data
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