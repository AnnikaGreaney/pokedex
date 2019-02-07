<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Genus;

class GeneraController extends Controller
{
    public function index()
    {
      return response()->json(Genus::all());
    }

    public function show($id)
    {
      return response()->json(Genus::with('pokemons')->where(['id' => $id])->get());
    }
}
