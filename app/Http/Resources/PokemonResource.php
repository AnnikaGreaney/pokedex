<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TypeResource;

class PokemonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      // Format Types
      $typesList = array();
      foreach($this->types as $type){
        array_push($typesList, [
          'System ID' => $type->id,
          'Name' => $type->name,
        ]);
      }

      // Format Abilities
      $abilitiesList = array();
      foreach($this->abilities as $ability){
        array_push($abilitiesList, [
          'System ID' => $ability->id,
          'Name' => $ability->name,
        ]);
      }

      return [
        'Pokedex Number' => $this->dex_number,
        'Name' => $this->name,
        'Height' => $this->height,
        'Weight' => $this->weight,
        'Genus' => $this->genus->title,
        'Type(s)' => $typesList,
        'Abilities' => $abilitiesList,
        'Statistics' => [
          'HP' => $this->hp,
          'Speed' => $this->speed,
          'Attack' => $this->attack,
          'Defense' => $this->defense,
          'Special Attack' => $this->special_attack,
          'Special Defense' => $this->special_defense
        ]
      ];
    }

}
