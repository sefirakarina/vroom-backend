<?php
namespace Tests\Unit;
use App\Car;
use App\Location;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class CarTest extends TestCase
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
            'coordinate' => "-37.806717, 144.965405",
            'slot' => 5,
            'current_car_num' =>1
        ]);
        factory(Location::class)->create([
            'id' => 2,
            'address' => "441 Lonsdale St, Melbourne VIC 3000",
            'coordinate' => "-37.813303, 144.959397",
            'slot' => 4,
            'current_car_num' =>0
        ]);

        factory(Car::class)->create([
            'id' => 1,
            'location_id' => 1,
            'plate' => "B1234H",
            'type' => "Toyota Avanza",
            'capacity' =>7,
            'availability' =>true
        ]);

        factory(Car::class)->create([
            'id' => 2,
            'location_id' => 2,
            'plate' => "B4352T",
            'type' => "Volkswagen Beetles",
            'capacity' =>5,
            'availability' =>false
        ]);

        $cars= Car::car();
        $this->assertCount(2, $cars);
        $this->assertEquals([
            [
                'id' => 1,
                'location_id' => 1,
                'plate' => "B1234H",
                'type' => "Toyota Avanza",
                'capacity' =>7,
                'availability' =>true
            ],
            [
                'id' => 2,
                'location_id' => 2,
                'plate' => "B4352T",
                'type' => "Volkswagen Beetles",
                'capacity' =>5,
                'availability' =>false
            ],
        ], $cars->toArray());
    }
}