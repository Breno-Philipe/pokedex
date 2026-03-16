<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center gap-3">
      <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" class="text-gray-700 hover:text-black transition-all duration-200 linear" title="Voltar">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8" />
        </svg>
      </a>
      <h1 class="font-semibold text-xl text-gray-800 leading-tight capitalize">{{ $pokemon->name }}</h1>
    </div>
  </x-slot>
  <div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow rounded p-6 text-center">
        <img src="{{ $pokemon->sprite }}" alt="{{ $pokemon->name }}" class="mx-auto w-40" />
        <h2 class="text-2xl font-bold capitalize mt-4">{{ $pokemon->name }}</h2>
        {{-- types --}}
        <div class="flex justify-center gap-2 mt-3">
          @foreach ($pokemon->types as $type)
            <x-pokemon-type-badge :type="$type->name" />
          @endforeach
        </div>
        {{-- abilities --}}
        <div class="mt-6">
          <h3 class="font-semibold mb-2">Abilities</h3>
          <div class="flex justify-center gap-2 flex-wrap">
            @foreach ($abilities as $ability)
              <span class="px-3 py-1 bg-gray-200 rounded text-sm capitalize">{{ $ability }}</span>
            @endforeach
          </div>
        </div>
        {{-- moves --}}
        <div class="mt-6">
          <h3 class="font-semibold mb-2">Moves</h3>
          <div class="flex justify-center gap-2 flex-wrap">
            @foreach ($moves as $move)
              <span class="px-3 py-1 bg-gray-200 rounded text-sm capitalize">{{ $move }}</span>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
