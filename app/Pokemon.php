<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Type;

class Pokemon extends Model
{
    public function types()
    {
      return $this->belongsToMany(Type::class, 'pokemon_types');
    }

    public function abilities()
    {
      return $this->belongsToMany(Ability::class, 'pokemon_abilities');
    }

    public function pokedexes()
    {
      return $this->hasMany(Pokedex::class);
    }

    public function genus()
    {
      return $this->belongsTo(Genus::class);
    }
}
