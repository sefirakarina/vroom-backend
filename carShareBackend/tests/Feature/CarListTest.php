<?php

namespace Tests\Feature;


use App\Car;
use App\Location;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CarListTest extends TestCase
{
    use DatabaseTransactions;

    public function testExample(){

        // return 'car not found' message if there is no car in the database
        $response = $this->call('HEAD', 'api/cars');
        $response->assertStatus(404);


        factory(Location::class)->create([
            'id' => 1,
            'address' => "78-56 Victoria St, Carlton VIC 3053",
            'coordinate' => "-37.806717, 144.965405",
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

        factory(Car::class)->create([
            'id' => 1,
            'location_id' => 1,
            'type'=>"Volkswagen Beetles",
            'plate' => "B121fd212",
            'capacity' => 4,
            'image_path'=>"./vroom-frontend/src/assets/volkswagen.PNG",
            'availability' =>true
        ]);

        //admin login to get token to manipulate car list
        $login = $this->call('POST', 'api/auth/login',
            [
                'email' => 'john@gmail.com',
                'password' => 'secret',
            ]
        );
        $login->assertStatus(200);

        //create new car with correct input
        $response = $this->call('POST', 'api/cars',
            [
                'location_id' => 1,
                'type'=>"some car",
                'plate' => "B543463",
                'capacity' => 4,
                'image_path'=>"./vroom-frontend/src/assets/aaaa.jpg",
                'availability' =>true
            ]
        );
        $response->assertStatus(200);
        $new_car_id = json_decode($response->getContent())->data->id;

        //create new car with incorrect input
        $response = $this->call('POST', 'api/cars',
            [
                'location_id' => 1,
                'type'=>"",
                'plate' => "",
                'capacity' => 4,
                'image_path'=>"./vroom-frontend/src/assets/aaaa.jpg",
                'availability' =>true
            ]
        );
        $response->assertStatus(404);

        // edit a car with correct input
        $response = $this->call('PATCH', 'api/cars/' . $new_car_id,
            [
                'location_id' => 1,
                'type'=>"Volkswagen Beetles",
                'plate' => "B124342",
                'capacity' => 5,
                'image_path'=>"./vroom-frontend/src/assets/volkswagen2.jpg",
                'availability' =>false
            ], $this->transformHeadersToServerVars(['Authorization' => $login->json("access_token")])
        );
        $response->assertStatus(200);

        //edit incorrect input
        $response = $this->call('PATCH', 'api/cars/' . $new_car_id ,
            [
                'location_id' => 1,
                'type'=>"",
                'plate' => "",
                'capacity' => 5,
                'image_path'=>"./vroom-frontend/src/assets/volkswagen2.jpg",
                'availability' =>false
            ], $this->transformHeadersToServerVars(['Authorization' => $login->json("access_token")])
        );
        $response->assertStatus(404);

        //get all cars
        $response = $this->call('HEAD', 'api/cars');
        $response->assertStatus(200);

        //delete a car
        $response = $this->call('DELETE', 'api/cars/1',
            $this->transformHeadersToServerVars(['Authorization' => $login->json("access_token")])
        );
        $response->assertStatus(200);

        //check the correct number of car
        $car = Car::car();
        $this->assertCount(1, $car);
        $response->assertStatus(200);

    }

}
