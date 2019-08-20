<?php

namespace Tests\Feature;


use App\Car;
use App\Location;
use Tests\TestCase;

class CarListTest extends TestCase
{
    public function testExample(){
        // return 'car not found' message if there is no car in the database
        $response = $this->call('HEAD', 'api/cars');

        $response->assertStatus(404);

        factory(Location::class)->create([
            'id' => 1,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'coordinate' => "-37.806717, 144.965405",
            'slot' => 5,
            'current_car_num' =>1
        ]);

        factory(Location::class)->create([
            'id' => 3,
            'address' => "5WVX+3G West Melbourne, Victoria",
            'coordinate' => "-37.807333, 144.948858",
            'slot' => 5,
            'current_car_num' =>1
        ]);

        factory(Car::class)->create([
            'id' => 1,
            'location_id' => 1,
            'type' => 'Volkswagen Beetles',
            'plate' => 'B121212',
            'capacity' => '4',
            'availability' => true
        ]);

        factory(Car::class)->create([
            'id' => 2,
            'location_id' => 3,
            'type'=>"Toyota Alphard",
            'plate' => "A234563",
            'capacity' => 6,
            'availability' =>true
        ]);

        // return a list of available cars
        $response = $this->call('HEAD', 'api/cars');

        $response->assertStatus(200);
    }
}
