<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pokedex extends Model
{

  protected $fillable = ['user_id', 'pokemon_id'];

  public function user()
  {
    $this->belongsTo(User::class);
  }

  public function pokemon()
  {
    $this->belongsTo(Pokemon::class);
  }
}
