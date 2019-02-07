<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource;

class PassportController extends Controller
{
  public function register(Request $request){
    $this->validate($request, [
      'name' => 'required',
      'email' => 'required|unique:users',
      'password' => 'required',
      'password_confirmation' => 'required|same:password'
    ]);
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password),
    ]);
    $token = $user->createToken('pokedex_api')->accessToken;

    return response()->json(['token' => $token], 200);
  }

  public function login(Request $request){
    $credentials = [
      'email' => $request->email,
      'password' => $request->password,
    ];
    if(auth()->attempt($credentials)){
      $token = auth()->user()->createToken('pokedex_api')->accessToken;
      return response()->json(['token' => $token], 200);
    } else{
      return response()->json(['error' => 'Unauthorized'], 401);
    }
  }

  public function details(){
    return response()->json(new UserResource(auth()->user()), 200);
  }
}
