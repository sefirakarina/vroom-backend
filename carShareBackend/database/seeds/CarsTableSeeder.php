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


    }
}
