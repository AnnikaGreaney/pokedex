<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genus extends Model
{
    public function pokemons()
    {
      $this->hasMany(Pokemon::class);
    }
}
