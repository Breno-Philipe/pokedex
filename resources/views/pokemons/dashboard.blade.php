<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pokédex</h2>
  </x-slot>
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- success message --}}
      @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">{{ session('success') }}</div>
      @endif
      {{-- actions --}}
      <div class="mb-6 flex gap-3">
        {{-- import button --}}
        @can('import', App\Models\Pokemon::class)
          <a href="{{ route('pokemons.import') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Importar Pokémons</a>
        @endcan
        {{-- delete all --}}
        @can('deleteAll', App\Models\Pokemon::class)
          <form action="{{ route('pokemons.destroy.all') }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="px-4 py-2 bg-red-600 text-white rounded" onclick="return confirm('Tem certeza que deseja apagar TODOS os pokémons?')">Apagar todos</button>
          </form>
        @endcan
        {{-- Filter input --}}
        <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
          <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar Pokémon" class="border rounded px-3 py-2">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Buscar</button>
        </form>
      </div>
      {{-- empty state --}}
      @if ($pokemons->isEmpty())
        <div class="p-6 bg-white shadow rounded">Ainda não foi importado nenhum pokémon.</div>
      @else
        {{-- grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
          @foreach ($pokemons as $pokemon)
            <x-pokemon.card :pokemon="$pokemon">
              <x-slot:actions>
                <x-pokemon.card-actions :pokemon="$pokemon" :showFavorite="true" :showDelete="true" :showDetails="true" :isFavorite="$pokemon->is_favorite" />
              </x-slot:actions>
            </x-pokemon.card>
          @endforeach
        </div>
        {{-- pagination --}}
        <div class="mt-6 flex justify-center">{{ $pokemons->appends(['search' => $search])->links() }}</div>
      @endif
    </div>
  </div>
</x-app-layout>
