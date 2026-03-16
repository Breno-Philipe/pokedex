<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Importar Pokémons</h2>
  </x-slot>
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- success message --}}
      @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded">{{ session('success') }}</div>
      @endif
      {{-- actions --}}
      <div class="mb-6 flex flex-wrap items-center gap-3">
        {{-- return to pokedex --}}
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded">Voltar para Pokédex</a>
        {{-- import batch --}}
        <form action="{{ route('pokemons.import.batch') }}" method="POST">
          @csrf
          @foreach ($pokemons as $pokemon)
            @if (!in_array($pokemon['name'], $imported))
              <input type="hidden" name="names[]" value="{{ $pokemon['name'] }}">
            @endif
          @endforeach
          <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Importar todos da página</button>
        </form>
        {{-- search --}}
        <form method="GET" action="{{ route('pokemons.import') }}" class="flex gap-2">
          <input type="text" name="search" value="{{ $search }}" placeholder="Buscar Pokémon (mínimo 3 letras)" class="border rounded px-3 py-2" />
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Buscar</button>
        </form>
      </div>
      {{-- error message --}}
      @if (!empty($error))
        <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded">{{ $error }}</div>
      @endif
      {{-- pokemon grid --}}
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach ($pokemons as $pokemon)
          <x-pokemon-import-card :pokemon="$pokemon" :imported="$imported" />
        @endforeach
      </div>
      {{-- pagination --}}
      <div class="mt-6 flex justify-center gap-4">
        @if ($page > 1)
          <a href="{{ route('pokemons.import', ['page' => $page - 1, 'search' => $search]) }}" class="px-4 py-2 bg-gray-300 rounded">< Anterior</a>
        @endif
        @if ($page * 20 < $count)
          <a href="{{ route('pokemons.import', ['page' => $page + 1, 'search' => $search]) }}" class="px-4 py-2 bg-gray-300 rounded">Próximo ></a>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>
