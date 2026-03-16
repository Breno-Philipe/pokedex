<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PokemonImportCard extends Component
{
    public array $pokemon;
    public bool $imported;

    public function __construct(array $pokemon, array $imported)
    {
        $this->pokemon = $pokemon;
        $this->imported = in_array($pokemon['name'], $imported);
    }

    public function render()
    {
        return view('components.pokemon-import-card');
    }
}