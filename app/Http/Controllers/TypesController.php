<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Pokemon;

class TypesController extends Controller
{
  public function index()
  {
    return response()->json(Type::all());
  }

  public function show($nameOrId)
  {
    if(is_numeric($nameOrId)){
      $pokemon = Type::find($nameOrId)->with('pokemons')->get();
    } else {
      $pokemon = Type::where(['name' => (string)$nameOrId])->with('pokemons')->get();
    }
    return response()->json($pokemon);
  }
}
