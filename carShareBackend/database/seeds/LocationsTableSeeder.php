<?php

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            'id' => 1,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'latitude' => -37.806717,
            'longitude' => 144.965405,
            'slot' => 5,
            'current_car_num' =>2
        ]);
        DB::table('locations')->insert([
            'id' => 2,
            'address' => "441 Lonsdale St, Melbourne VIC 3000",
            'latitude' => -37.813303,
            'longitude' =>144.959397,
            'slot' => 4,
            'current_car_num' =>1
        ]);
        DB::table('locations')->insert([
            'id' => 3,
            'address' => "5WVX+3G West Melbourne, Victoria",
            'latitude' => -37.807333,
            'longitude' =>144.948858,
            'slot' => 5,
            'current_car_num' =>3
        ]);

        DB::table('locations')->insert([
            'id' => 4,
            'address' => "28 Freshwater Pl, Southbank VIC 3006",
            'latitude' => -37.822997,
            'longitude' =>144.961970,
            'slot' => 2,
            'current_car_num' =>1
        ]);

    }
}
