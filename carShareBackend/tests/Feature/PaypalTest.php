<?php

namespace Tests\Feature;


use App\Car;
use App\Location;
use App\User;
use App\Booking;
use App\Customer;
use App\History;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Datetime;

class CreateUpdateBookingTest extends TestCase
{
    use DatabaseTransactions;

    public function testExample(){


        factory(Location::class)->create([
            'id' => 2,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'latitude' => -37.806717,
            'longitude' => 144.965405,
            'slot' => 5,
            'current_car_num' => 0
        ]);


        factory(User::class)->create([
            'id' => 2,
            'name' => "john",
            'email' => 'john@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'admin'
        ]);

        factory(User::class)->create([
            'id' => 3,
            'name' => "jane",
            'email' => 'jane@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'customer'
        ]);

        factory(Customer::class)->create([
            'id' => 1,
            'user_id' => 3,
            'address' => "P Sherman 42 Wallaby Way, Sydney",
            'phone_number' => "04010204",
            'license_number' =>"123456",
            'status'=> true
        ]);

        factory(Car::class)->create([
            'id' => 1,
            'location_id' => 2,
            'type'=>"Volkswagen Beetles",
            'plate' => "B121fd212",
            'capacity' => 4,
            'image_path'=>"./vroom-frontend/src/assets/volkswagen.PNG",
            'availability' =>true,
            'price_per_day'=> 80
        ]);

        $login = $this->call('POST', 'api/auth/login',
            [
                'email' => 'jane@gmail.com',
                'password' => 'secret',
            ]
        );
        $login->assertStatus(200);

        $response = $this->call('POST', 'api/payment/create/',
            [
                'car_id' => 1,
                'rentDays' => 2
            ], $this->transformHeadersToServerVars(['Authorization' => $login->json("access_token")])
        );
        //$response->assertStatus(302);
        dd($response->getContent());



    }

}
