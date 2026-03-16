<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request responsible for validating batch Pokémon import operations.
 *
 * This request ensures that a valid list of Pokémon names is provided
 * before performing bulk imports from the PokéAPI.
 *
 * The list of names is sanitized before validation to ensure
 * consistent formatting when sending requests to the API.
 */
class BatchImportPokemonRequest extends FormRequest
{
    /**
     * Determine whether the authenticated user is authorized
     * to perform batch Pokémon imports.
     *
     * Only users with the roles "editor" or "admin"
     * are allowed to execute this operation.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()?->isEditor() || auth()->user()?->isAdmin();
    }

    /**
     * Validation rules for the request.
     *
     * Ensures that:
     * - at least one Pokémon name is provided
     * - each name is a valid string
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'names' => ['required', 'array', 'min:1'],
            'names.*' => ['required', 'string', 'min:2', 'max:100']
        ];
    }

    /**
     * Prepare the request data for validation.
     *
     * Normalizes the Pokémon names by trimming whitespace
     * and converting them to lowercase.
     *
     * This ensures consistency when querying the PokéAPI.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('names')) {

            $sanitized = collect($this->input('names'))
                ->map(fn ($name) => strtolower(trim($name)))
                ->filter()
                ->values()
                ->toArray();

            $this->merge([
                'names' => $sanitized
            ]);
        }
    }

    /**
     * Custom attribute names used in validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'names' => 'lista de pokémons',
            'names.*' => 'nome do pokémon'
        ];
    }
}