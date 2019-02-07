<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:api')->get('/user', function (Request $request) {
   return $request->user();
});

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');
Route::middleware('auth:api')->group(function(){
  Route::get('user', 'PassportController@details');
  Route::post('pokemon/{pokemon}/captured', 'PokemonsController@captured');
  Route::apiResource('pokemon','PokemonsController');
  Route::get('abilities','AbilitiesController@index');
  Route::get('abilities/{ability}', 'AbilitiesController@show');
  Route::get('types','TypesController@index');
  Route::get('types/{type}','TypesController@pokemonByType');
});

Route::fallback(function(){
    return response()->json(['message' => 'Not Found.'], 404);
})->name('api.fallback.404');
