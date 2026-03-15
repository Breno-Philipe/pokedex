<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PokeApiClient
{
    private string $baseUrl;
    private const CACHE_TTL_SECONDS = 300;
    private const TIMEOUT_SECONDS = 10;

    public function __construct()
    {
        $this->baseUrl = config('services.pokeapi.base_url');
    }

    /**
     * Get a paginated list of pokemons from the PokéAPI.
     *
     * @param int $limit
     * @param int $offset
     * @return array<string, mixed>
     *
     * @throws \RuntimeException
     */
    public function listPokemons(int $limit = 20, int $offset = 0): array
    {
        $cacheKey = "pokeapi.pokemons.list.{$limit}.{$offset}";

        return Cache::remember(
            $cacheKey,
            self::CACHE_TTL_SECONDS,
            fn () => $this->request('pokemon', [
                'limit' => $limit,
                'offset' => $offset,
            ])
        );
    }

    /**
     * Get a pokemon by name from the PokéAPI.
     *
     * @param string $name
     * @return array<string, mixed>
     *
     * @throws \RuntimeException
     */
    public function getPokemonByName(string $name): array
    {
        $normalizedName = mb_strtolower(trim($name));
        $cacheKey = "pokeapi.pokemon.{$normalizedName}";

        return Cache::remember(
            $cacheKey,
            self::CACHE_TTL_SECONDS,
            fn () => $this->request("pokemon/{$normalizedName}")
        );
    }

    /**
     * Perform a GET request to the PokéAPI.
     *
     * @param string $endpoint
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     *
     * @throws \RuntimeException
     */
    private function request(string $endpoint, array $query = []): array
    {
        $url = sprintf('%s/%s', $this->baseUrl, ltrim($endpoint, '/'));

        try {
            $response = Http::timeout(self::TIMEOUT_SECONDS)
                ->retry(2, 200)
                ->acceptJson()
                ->withHeaders([
                    'User-Agent' => 'Laravel-Pokedex-App'
                ])
                ->get($url, $query);

            $response->throw();

            return $response->json();
        } catch (RequestException $exception) {
            Log::error('PokéAPI request failed', [
                'url' => $url,
                'query' => $query,
                'status' => $exception->response?->status(),
                'message' => $exception->getMessage(),
            ]);

            throw new \RuntimeException(
                'Não foi possível consultar a PokéAPI no momento. Tente novamente em instantes.'
            );
        } catch (\Throwable $exception) {
            Log::error('Unexpected PokéAPI integration error', [
                'url' => $url,
                'query' => $query,
                'message' => $exception->getMessage(),
            ]);

            throw new \RuntimeException(
                'Ocorreu um erro inesperado ao consultar a PokéAPI.'
            );
        }
    }
}
