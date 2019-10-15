<?php

namespace Tests\Feature;


use App\Car;
use App\CreditCard;
use App\Customer;
use App\History;
use App\Location;
use App\User;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HistoriesListTest extends TestCase
{
    use DatabaseTransactions;

    // 40. As an admin, I want to see all  customer's booking histories
    public function testExample(){
        // Create user, customer, credit card, location and car data
        factory(User::class)->create([
            'id' => 1,
            'name' => "aaa",
            'email' => 'aaa@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'customer'
        ]);
        factory(Customer::class)->create([
            'id' => 1,
            'user_id' => 1,
            'address' => "P Sherman 42 Wallaby Way, Sydney",
            'phone_number' => "04010204",
            'license_number' =>"123456",
            'status'=> true
        ]);
        factory(CreditCard::class)->create([
            'id' => 1,
            'customer_id' => 1,
            'number' => "1234567812345678",
            'name' => "aaa",
            'exp_date' => "10/20"
        ]);
        factory(Location::class)->create([
            'id' => 1,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'latitude' => -37.806717,
            'longitude' => 144.965405,
            'slot' => 5,
            'current_car_num' => 1
        ]);
        factory(Car::class)->create([
            'id' => 1,
            'location_id' => 1,
            'type'=>"Volkswagen Beetles",
            'plate' => "B121212",
            'capacity' => 4,
            'price_per_day' => 80,
            'image_path'=>"test",
            'availability' => 1
        ]);

        //create booking history
        factory(History::class)->create([
            'id' => 1,
            'customer_id' => 1,
            'car_id' => 1,
            'return_location_id' => 1,
            'begin_time' => new DateTime('2019-09-27 14:30:12'),
            'return_time' =>new DateTime('2019-09-28 12:30:12')
        ]);

        // Login as customer
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'aaa@gmail.com',
                'password' => 'secret',
            ]
        );
        $response->assertStatus(200);

        // Display all booking histories
        $response = $this->call('GET', 'api/histories');
        $response->assertStatus(200);
    }
}
