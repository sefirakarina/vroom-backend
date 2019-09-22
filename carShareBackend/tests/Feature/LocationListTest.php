<?php

namespace Tests\Feature;


use App\Location;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LocationListTest extends TestCase
{
    use DatabaseTransactions;

    // As an admin, I want to be able to see the list of locations
    public function testExample(){
        // return 'location not found' message if there is no location in the database
        $response = $this->call('HEAD', 'api/locations');
        $response->assertStatus(404);

        // location data
        factory(Location::class)->create([
            'id' => 1,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'latitude' => -37.806717,
            'longitude' => 144.965405,
            'slot' => 5,
            'current_car_num' => 0
        ]);

        factory(Location::class)->create([
            'id' => 2,'latitude' => -37.806717,
            'longitude' => 144.965405,
            'address' => "441 Lonsdale St, Melbourne VIC 3000",
            'coordinate' => "-37.813303, 144.959397",
            'slot' => 4,
            'current_car_num' => 1
        ]);

        // get location list
        $response = $this->call('HEAD', 'api/locations');
        $response->assertStatus(200);
    }
}
