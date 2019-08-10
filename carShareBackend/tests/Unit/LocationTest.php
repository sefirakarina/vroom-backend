<?php
namespace Tests\Unit;
use App\Location;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;
class LocationTest extends TestCase
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
        $locations = Location::location();
        $this->assertCount(2, $locations);
        $this->assertEquals([
            [
                'id' => 1,
                'address' => "78-56 Victoria St, Carlton VIC 3053",
                'coordinate' => "-37.806717, 144.965405",
                'slot' => 5,
                'current_car_num' =>1
            ],
            [
                'id' => 2,
                'address' => "441 Lonsdale St, Melbourne VIC 3000",
                'coordinate' => "-37.813303, 144.959397",
                'slot' => 4,
                'current_car_num' =>0
            ],
        ], $locations->toArray());
    }
}