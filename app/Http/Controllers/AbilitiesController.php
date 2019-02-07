<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ability;

class AbilitiesController extends Controller
{
    public function index()
    {
      return response()->json(Ability::all());
    }

    public function show($nameOrId)
    {
      if(is_numeric($nameOrId)){
        $pokemon = Ability::with('pokemons')->where(['id' => $nameOrid])->get();
      } else {
        $pokemon = Ability::where(['name' => $nameOrId])->with('pokemons')->get();
      }
      return response()->json($pokemon);
    }
}
