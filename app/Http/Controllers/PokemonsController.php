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

    public function captured($idOrName)
    {
      $user = auth()->user();
      if(is_numeric($idOrName)){
        $pokemon = Pokemon::find($idOrName);
      } else {
        $pokemon = Pokemon::whereName($idOrName)->first();
      }
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

    public function attackers()
    {
      $attackers = Pokemon::where('attack','>=', 100)->get();
      return response()->json($attackers);
    }

    public function defenders()
    {
      $defenders = Pokemon::where('defense', '>=', 100)->get();
      return response()->json($defenders);
    }

    public function battle(Request $request)
    {
      $this->validate($request, [
        'attacker' => 'required',
        'defender' => 'required'
      ]);

      // Build Attacker
      $attacker = is_numeric($request->attacker) ? Pokemon::find($request->attacker) : Pokemon::whereName($request->attacker)->first();

      // Build Defender
      $defender = is_numeric($request->defender) ? Pokemon::find($request->defender) : Pokemon::whereName($request->defender)->first();

      if(!$attacker || !$defender){
        return response()->json(['error' => 'Please specify valid pokemon']);
      }
      // Tally Rounds
      $round_winner = array();

      for ($i=0; $i < 100; $i++) {
        $aHp = $attacker->hp;
        $dHp = $defender->hp;
        $roundCounter = 1.0;

        while(($aHp > 0) && ($dHp > 0)){
          // Check who attacks - attacker always starts
          $hits = array();
          // Randomizer for special defense/attack - 20% chance
          // 1 & 2 mean specials don't happen at the same time for both parties
          $special = rand(1,5);

          $aAttack = $special === 1 ? $attacker->special_attack : $attacker->attack;
          $aDefense = $special === 1 ? $attacker->special_defense : $attacker->defense;
          $dAttack = $special === 2 ? $defender->special_attack : $defender->attack;
          $dDefense = $special === 2 ? $defender->special_defense : $defender->defense;

          // 20% chance of missing
          if($special === 5){
            continue;
          }

          // Deal damage
          if($roundCounter % 2 != 0){
            if($aAttack > $dDefense){
              $dHp = $dHp - ($aAttack - $dDefense);
            } else {
              $dHp = $dHp - (($dDefense - $aAttack)*.2);
            }
          } else {
            if($dAttack > $aDefense){
              $aHp = $aHp - ($dAttack - $aDefense);
            } else {
              $aHp = $aHp - (($aDefense - $dAttack)*.2);
            }
          }
          $roundCounter += 1;
        }

        $winner = $aHp > 0 ? $attacker : $defender;
        array_push($round_winner, $winner->name);
      }

      // Calculate Probability for attacker to win
      $results = array_count_values($round_winner);
      $probability = $results[$attacker->name];

      return response()->json(['attacker' => $attacker->name, 'defender' => $defender->name, 'attacker_probability' => $probability.'%']);
    }
}
