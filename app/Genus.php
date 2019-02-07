<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genus extends Model
{
    public function pokemons()
    {
      return $this->hasMany(Pokemon::class);
    }
}
