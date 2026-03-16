<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Pokemon;

class PokemonCard extends Component
{
    public Pokemon $pokemon;

    public function __construct(Pokemon $pokemon)
    {
        $this->pokemon = $pokemon;
    }

    public function render()
    {
        return view('components.pokemon.card');
    }
}