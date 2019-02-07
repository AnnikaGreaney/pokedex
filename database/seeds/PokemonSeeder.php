<?php

use Illuminate\Database\Seeder;
use Keboola\Csv\CsvReader;
use App\Pokemon;
use App\Genus;
use App\Type;
use App\PokemonType;
use App\Ability;
use App\PokemonAbility;

class PokemonSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $file_path = __DIR__.'/../csv/pokemon.csv';
    $csv = new CsvReader($file_path,CsvReader::DEFAULT_DELIMITER,CsvReader::DEFAULT_ENCLOSURE,CsvReader::DEFAULT_ESCAPED_BY,1);
    foreach($csv as $row){
      // Characters to be removed for building arrays
      $sanitize_characters = array('"','[',']','{','}');

      // Genus
      $genus = Genus::firstOrCreate(['title' => $row[8]]);

      // Build useable Stats array
      $stats = explode(', ', str_replace($sanitize_characters,"",$row[7]));
      for ($i=0; $i < sizeof($stats); $i++) {
        $stat = explode(' ',$stats[$i])[1];
        $stats[$i] = $stat;
      }

      // Create the Pokemon
      $pokemon = Pokemon::create([
        'name' => $row[1],
        'dex_number' => $row[0],
        'height' => $row[3],
        'weight' => $row[4],
        'hp' => $stats[0],
        'speed' => $stats[1],
        'attack' => $stats[2],
        'defense' => $stats[3],
        'special_attack' => $stats[4],
        'special_defense' => $stats[5],
        'genus_id' => $genus->id,
        'description' => $row[9]
      ]);

      // Assign the Type(s)
      // Build array from string
      $types = explode(', ', str_replace($sanitize_characters,"",$row[2]));

      // Assign Types to Pokemon
      foreach($types as $type){
        $t = Type::firstOrCreate(['name' => $type]);
        PokemonType::create([
          'pokemon_id' => $pokemon->id,
          'type_id' => $t->id,
        ]);
      }

      // Assign the Abilities
      $abilities = explode(', ', str_replace($sanitize_characters,"",$row[5]));

      // Assign Abilities to Pokemon
      foreach($abilities as $ability){
        $a = Ability::firstOrCreate(['name' => $ability]);
        PokemonAbility::create([
          'pokemon_id' => $pokemon->id,
          'ability_id' => $a->id,
        ]);
      }

    }
  }
}
