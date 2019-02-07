<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Pokemon;
use App\Http\Resources\PokemonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      $pokemons = array();
      foreach($this->pokedexes as $dex_entry){
        $pokemon = Pokemon::find($dex_entry->pokemon_id);
        array_push($pokemons, new PokemonResource($pokemon));
      };
        return [
          'name' => $this->name,
          'caught_pokemon' => $pokemons,
        ];
    }
}
