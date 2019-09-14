<?php

namespace Tests\Feature;


use App\Location;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LocationDetailsTest extends TestCase
{
    use DatabaseTransactions;

    // As an admin, I want to be able to see the details of a location
    public function testExample(){
        //create admin account
        $admin = factory(User::class)->create([
            'id' => 999,
            'name' => "Sue",
            'email' => 'Sue@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'admin'
        ]);

        //login with the admin account
        $login = $this->call('POST', 'api/auth/login',
            [
                'email' => 'Sue@gmail.com',
                'password' => 'secret',
            ]
        );
        $login->assertStatus(200);


        // return 'location not found' message if the location id doesn't exits in the database
        $response = $this->call('GET', 'api/locations/1');
        $response->assertStatus(404);


        // insert location details into database
        factory(Location::class)->create([
            'id' => 1,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'coordinate' => "-37.806717, 144.965405",
            'slot' => 5,
            'current_car_num' => 2
        ]);


        // See location details
        $response = $this->call('GET', 'api/cars/locations/1',
            $this->transformHeadersToServerVars([ 'Authorization' => $login->json("access_token")])
        );
        $response->assertStatus(200);

    }
}
