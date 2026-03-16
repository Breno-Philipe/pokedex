@php
$colors = [
'grass' => 'bg-green-500',
'fire' => 'bg-red-500',
'water' => 'bg-blue-500',
'electric' => 'bg-yellow-400',
'bug' => 'bg-lime-500',
'poison' => 'bg-purple-500',
'flying' => 'bg-indigo-400',
'normal' => 'bg-gray-400',
'fairy' => 'bg-pink-400',
'ground' => 'bg-yellow-900',
];

$color = $colors[$type] ?? 'bg-gray-500';
@endphp

<span class="px-3 py-1 text-white text-xs rounded-full {{ $color }}">
{{ $type }}
</span>