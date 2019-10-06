<?php
namespace Tests\Unit;
use App\Booking;
use App\Car;
use App\History;
use App\Location;
use App\User;
use App\Customer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class HistoryTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {

        factory(Location::class)->create([
            'id' => 1,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'latitude' => -37.806717,
            'longitude' => 144.965405,
            'slot' => 5,
            'current_car_num' =>2
        ]);

        factory(Car::class)->create([
            'id' => 1,
            'location_id' => 1,
            'plate' => "B1234H",
            'type' => "Toyota Avanza",
            'image_path'=>"img/imagename.jpg",
            'capacity' =>7,
            'availability' =>true,
            'price_per_day'=> 80
        ]);

        factory(User::class)->create([
            'id' => 1,
            'name' => "Sue",
            'email' => 'Sue@gmail.com',
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

        factory(History::class)->create([
            'id' => 1,
            'customer_id' => 1,
            'car_id' => 1,
            'return_location_id' => 1,
            'begin_time' => '2019-10-27 14:30:12',
            'return_time' =>'2019-10-28 12:30:12'
        ]);

        $history= History::history();
        $this->assertCount(1, $history);
        $this->assertEquals([
            [
                'id' => 1,
                'customer_id' => 1,
                'car_id' => 1,
                'return_location_id' => 1,
                'begin_time' => '2019-10-27 14:30:12',
                'return_time' =>'2019-10-28 12:30:12'
            ],
        ], $history->toArray());
    }
}