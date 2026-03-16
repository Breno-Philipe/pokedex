@props(['pokemon', 'showTypes' => true])
<div class="bg-white shadow rounded p-4 text-center">
  <img src="{{ $pokemon->sprite }}" alt="{{ $pokemon->name }}" class="mx-auto w-24 h-24" />
  <h3 class="mt-2 font-semibold capitalize">{{ $pokemon->name }}</h3>
  @if ($showTypes && isset($pokemon->types))
    <div class="text-sm text-gray-500 mt-1">
      @foreach ($pokemon->types as $type)
        <x-pokemon-type-badge :type="$type->name" />
      @endforeach
    </div>
  @endif
  <div class="flex justify-center gap-3 mt-3">{{ $actions ?? '' }}</div>
</div>
