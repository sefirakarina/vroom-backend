<?php

use Illuminate\Database\Seeder;

class CarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cars')->insert([
            'id' => 1,
            'location_id' => 1,
            'type'=>"Volkswagen Beetles",
            'plate' => "B121212",
            'capacity' => 4,
            'availability' =>true
        ]);

        DB::table('cars')->insert([
            'id' => 2,
            'location_id' => 3,
            'type'=>"Toyota Alphard",
            'plate' => "A234563",
            'capacity' => 6,
            'availability' =>true
        ]);

        DB::table('cars')->insert([
            'id' => 3,
            'location_id' => 2,
            'type'=>"Toyota Corolla",
            'plate' => "C12342",
            'capacity' => 5,
            'availability' =>true
        ]);
        DB::table('cars')->insert([
            'id' => 4,
            'location_id' => 1,
            'type'=>"Honda Civic",
            'plate' => "F12535",
            'capacity' => 5,
            'availability' =>true
        ]);
        DB::table('cars')->insert([
            'id' => 5,
            'location_id' => 3,
            'type'=>"Honda Jazz",
            'plate' => "G352414",
            'capacity' => 5,
            'availability' =>true
        ]);
        DB::table('cars')->insert([
            'id' => 6,
            'location_id' => 4,
            'type'=>"Honda Jazz",
            'plate' => "P352414",
            'capacity' => 5,
            'availability' =>true
        ]);

    }
}
