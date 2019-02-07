<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public function pokemons()
    {
      return $this->belongsToMany(Pokemon::class, 'pokemon_types');
    }
}
