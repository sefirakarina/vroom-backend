<?php

namespace Tests\Feature;


use DateTime;

use App\Role;

use App\User;

use App\Customer;

use App\PasswordReset;

use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Hash;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Foundation\Testing\RefreshDatabase;



class ResetPasswordTest extends TestCase

{

    use DatabaseTransactions;



    /**

     * Create admin and user 

     *

     * @return void

     */

    public function testExample()

    {     
        
        //create admin 1 account
        $admin = factory(User::class)->create([
            'id' => 1,
            'name' => "Vroom",
            'email' => 'vroomapi123@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'customer'
        ]);
        
        //create customer 1 account
        $customer = factory(Customer::class)->create([
        'id' => 1,
        'user_id' => 1,
        'address' => "P Sherman 42 Wallaby Way, Sydney",
        'phone_number' => "04010204",
        'license_number' =>"123436",
        'status'=> true
        ]);

        //create admin 2 account
        $admin = factory(User::class)->create([
            'id' => 2,
            'name' => "test",
            'email' => 'test@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'customer'
        ]);
        
        //create customer 2 account
        $customer = factory(Customer::class)->create([
        'id' => 2,
        'user_id' => 2,
        'address' => "P Sherman 42 Wallaby Way, Sydney",
        'phone_number' => "04010204",
        'license_number' =>"123456",
        'status'=> true
        ]);       
        
        //create token for user 1 
        factory(PasswordReset::class)->create([
            'email' => "vroomapi123@gmail.com",
            'token' => "123123",
            'created_at' => new DateTime('2019-09-27 14:30:12'),

        ]);
        
        // Test create
        $response = $this->call('POST', 'api/password/create',
        [
            'email' => "test@gmail.com"
        ]);
        $response->assertStatus(200);

        // Test find 
        $response = $this->call('GET', 'api/password/find/123123'
            );
        $response->assertStatus(200);

        // Test reset 
        $response = $this->call('POST', 'api/password/reset',
        [
            'email' => "vroomapi123@gmail.com",
            'password' => "secret",
            'password_confirmation' => "secret",
            'token' => "123123"
        ]);
        $response->assertStatus(200);
    }
}