<?php



namespace Tests\Feature;


use DateTime;

use App\Role;

use App\User;

use App\Booking;

use App\Car;

use App\Customer;

use App\Location;

use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Hash;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Foundation\Testing\RefreshDatabase;



class showMyBookingTest extends TestCase

{

    use DatabaseTransactions;



    /**

     * Create admin and user 

     *

     * @return void

     */

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
            'price_per_day'=>6,
            'availability' =>true
        ]);
        
        
        //create admin account
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
        'status'=> true
        ]);

        //create booking
        factory(Booking::class)->create([
            'id' => 1,
            'customer_id' => 1,
            'car_id' => 1,
            'return_location_id' => 1,
            'begin_time' => new DateTime('2019-09-27 14:30:12'),
            'return_time' =>new DateTime('2019-09-28 12:30:12'),
            'status' => false,
            'payment_status' => true
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

        //display mybookings
        $response = $this->get('api/bookings/status/1/false');
        $response->assertStatus(200);
    }
}