<?php



namespace Tests\Feature;



use App\Role;

use App\User;

use App\Customer;

use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Hash;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Foundation\Testing\RefreshDatabase;



class UpdateStatusTest extends TestCase

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
        
        //create customer account
        $customer = factory(User::class)->create([
            'id' => 99,
            'name' => "Zoe",
            'email' => 'Zoe@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'customer'
        ]);
        //fill customer details
        $customer = factory(Customer::class)->create([
            'id' => 99,
            'user_id' =>99,
            'address' => 'test',
            'phone_number' => '911',
            'license_number' => '123',
            'status' => false
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
        $response = $this->call('PUT', 'api/users/activate/99');
        $response->assertStatus(200);



    }

}