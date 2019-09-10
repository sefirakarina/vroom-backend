<?php



namespace Tests\Feature;



use App\Role;

use App\User;

use App\Location;

use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Hash;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Foundation\Testing\RefreshDatabase;



class LocationTest extends TestCase

{

    use DatabaseTransactions;



    /**

     * Create admin and user 

     *

     * @return void

     */

    public function testExample()

    {
        //create admin account
        $admin = factory(User::class)->create([
            'id' => 999,
            'name' => "Sue",
            'email' => 'Sue@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'admin'
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
        
        //Update the customer status
        $response = $this->call('POST', 'api/locations',
        [
            'address' => 'test',
            'latitude' => -35.1323214,
            'longitude' => 144.121312,
            'slot' => '5'
        ]);
        $response->assertStatus(200);

        $location = Location::location();
        $this->assertCount(1, $location);
        $response->assertStatus(200);

    }

}