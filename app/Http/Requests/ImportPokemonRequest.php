<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request responsible for handling Pokémon import validation.
 *
 * This request validates and sanitizes the Pokémon name received
 * through the route parameter before the controller processes
 * the import operation.
 *
 * The Pokémon name is normalized to lowercase and trimmed
 * to ensure consistent API requests.
 */
class ImportPokemonRequest extends FormRequest
{
    /**
     * Determine whether the authenticated user is authorized
     * to perform this request.
     *
     * Authorization is handled through route middleware
     * using the PokemonPolicy.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for the request.
     *
     * Since the Pokémon name comes from the route parameter,
     * no body validation rules are required.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Prepare the request data for validation.
     *
     * Sanitizes the Pokémon name received from the route
     * by trimming whitespace and converting it to lowercase.
     *
     * This ensures consistent queries to the PokéAPI.
     */
    protected function prepareForValidation(): void
    {
        $name = $this->route('name');

        if ($name) {
            $this->merge([
                'name' => strtolower(trim($name))
            ]);
        }
    }

    /**
     * Retrieve the sanitized Pokémon name.
     *
     * @return string
     */
    public function pokemonName(): string
    {
        return $this->input('name');
    }
}