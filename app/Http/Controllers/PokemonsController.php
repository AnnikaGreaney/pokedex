<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pokemon;
use App\Pokedex;
use App\Http\Resources\PokemonResource;

class PokemonsController extends Controller
{
    public function index()
    {
      return response()->json(PokemonResource::collection(Pokemon::paginate(25)));
    }

    public function show($idOrName)
    {
      if(is_numeric($idOrName)){
        $pokemon = Pokemon::find($idOrName);
      } else {
        $pokemon = Pokemon::whereName($idOrName)->first();
      }
      if($pokemon){
        return response()->json(new PokemonResource($pokemon));
      } else {
        return response()->json(['error' => 'No Pokemon found']);
      }
    }

    public function captured($id)
    {
      $user = auth()->user();
      $pokemon = Pokemon::find($id);
      $already_caught = Pokedex::where(['user_id' => $user->id, 'pokemon_id' => $id])->get();
      if(sizeof($already_caught) > 0){
        $dex_entry = $already_caught[0];
        $message_type = 'error';
        $message = $user->name.', you already caught '.$pokemon->name.' on '.date_format($dex_entry->created_at,'F jS, Y');
      } else {
        $dex_entry = Pokedex::create([
          'user_id' => $user->id,
          'pokemon_id' => $id,
        ]);
        $message_type = 'success';
        $message = $pokemon->name.' has been marked as captured';
      };
      return response()->json([$message_type => $message]);

    }
}
