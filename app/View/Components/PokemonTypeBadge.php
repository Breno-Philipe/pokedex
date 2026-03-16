<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PokemonTypeBadge extends Component
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function render()
    {
        return view('components.pokemon.type-badge');
    }
}