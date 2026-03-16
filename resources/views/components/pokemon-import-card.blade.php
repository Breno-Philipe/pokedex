@php
  $id = basename(rtrim($pokemon['url'], '/'));
@endphp
<div class="bg-white shadow rounded p-4 text-center">
  <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{{ $id }}.png"
    alt="{{ $pokemon['name'] }}" class="mx-auto w-24 h-24" />
  <h3 class="mt-2 font-semibold capitalize">
    {{ $pokemon['name'] }}
  </h3>
  <form action="{{ route('pokemons.import.one', $pokemon['name']) }}" method="POST" class="mt-3">
    @csrf
    <button type="submit" title="{{ $imported ? 'Já importado' : 'Importar Pokémon' }}" {{ $imported ? 'disabled' : '' }}
      class="mx-auto">
      @if ($imported)
        {{-- imported --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
          class="w-6 h-6 text-gray-400 mx-auto">
          <path d="M9 16.17L4.83 12l-1.42 1.41L9 21 21 9l-1.41-1.41z" />
        </svg>
      @else
        {{-- import --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24" class="w-6 h-6 text-green-600 hover:scale-110 transition mx-auto">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
      @endif
    </button>
  </form>
</div>
