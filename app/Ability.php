<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
  public function pokemons()
  {
    return $this->belongsToMany(Pokemon::class, 'pokemon_abilities');
  }
}
