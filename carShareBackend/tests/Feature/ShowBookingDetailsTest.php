<?php

namespace Tests;


use App\Booking;
use App\Car;
use App\CreditCard;
use App\Customer;
use App\Location;
use App\User;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

class ShowBookingDetailsTest extends TestCase
{
    use DatabaseTransactions;

    /* 39. As an admin, I want to see details of a booking */

    public function testExample()
    {
        //create location
        factory(Location::class)->create([
            'id' => 1,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'latitude' => -37.806717,
            'longitude' => 144.965405,
            'slot' => 5,
            'current_car_num' => 1
        ]);

        //create car
        factory(Car::class)->create([
            'id' => 1,
            'location_id' => 1,
            'type'=>"Volkswagen Beetles",
            'plate' => "B121212",
            'capacity' => 4,
            'image_path'=>"test",
            'availability' => 1
        ]);


        //create account
        $admin = factory(User::class)->create([
            'id' => 1,
            'name' => "Sue",
            'email' => 'Sue@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'customer'
        ]);

        //create customer account
        $customer = factory(Customer::class)->create([
            'id' => 1,
            'user_id' => 1,
            'address' => "P Sherman 42 Wallaby Way, Sydney",
            'phone_number' => "04010204",
            'license_number' =>"123456",
            'status'=> 1
        ]);

        // Create credit card
        $card = factory(CreditCard::class)->create([
            'id' => 1,
            'customer_id' => 1,
            'number' => "1234567812345678",
            'name' => "Sue",
            'exp_date' => "10/20"
        ]);

        //create booking
        factory(Booking::class)->create([
            'id' => 1,
            'customer_id' => 1,
            'car_id' => 1,
            'return_location_id' => 1,
            'begin_time' => new DateTime('2019-09-27 14:30:12'),
            'return_time' =>new DateTime('2019-09-28 12:30:12'),
            'status' => 0
        ]);

        //login the admin account
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'Sue@gmail.com',
                'password' => 'secret',
            ]
        );
        $response->assertStatus(200);

        $response = $this->call('POST', 'api/auth/me',

            $this->transformHeadersToServerVars([ 'Authorization' => $response->json("access_token")])

        );

        $response->assertStatus(200);

        // display booking details
        $response = $this->get('api/bookings/1');
        $response->assertStatus(200);
    }
}
