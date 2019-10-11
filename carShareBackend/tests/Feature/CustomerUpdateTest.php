<?php

namespace Tests\Feature;


use App\Customer;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /* 28. As a customer, I want to be able to edit my profile information */
    public function testExample(){
        // Create customer in User and Customer table
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

        // Login as customer
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'aaa@gmail.com',
                'password' => 'secret',
            ]
        );
        $response->assertStatus(200);

        // Update customer details
        $response = $this->call('PUT', 'api/customers/1',
            [
                'address' => "A street in Melbourne",
                'phone_number' => "04000000"
            ]
        );
        $response->assertStatus(200);

    }
}
