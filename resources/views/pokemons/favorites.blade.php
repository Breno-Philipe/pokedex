<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pokémons Favoritos</h2>
  </x-slot>
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- success message --}}
      @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">{{ session('success') }}</div>
      @endif
      {{-- empty state --}}
      @if ($pokemons->isEmpty())
        <div class="p-6 bg-white shadow rounded text-center">Você ainda não possui pokémons favoritados.</div>
      @else
        {{-- grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
          @foreach ($pokemons as $pokemon)
            <x-pokemon.card :pokemon="$pokemon">
              <x-slot:actions>
                <x-pokemon.card-actions :pokemon="$pokemon" :showFavorite="true" :showDelete="true" :showDetails="true" :isFavorite="true" />
              </x-slot:actions>
            </x-pokemon.card>
          @endforeach
        </div>
        {{-- pagination --}}
        <div class="mt-6 flex justify-center">{{ $pokemons->links() }}</div>
      @endif
    </div>
  </div>
</x-app-layout>
