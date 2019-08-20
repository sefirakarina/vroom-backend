<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    public function testExample()
    {
        factory(User::class)->create([
            'id' => 2,
            'name' => "testCustomer2",
            'email' => 'test2@test.com',
            'password' => Hash::make("secret"),
            'role' => 'customer'
        ]);


        // customer registration test
        // 1 - with non-existing username
        $response = $this->call('POST', 'api/customers',
            [
                'name' => 'testCustomer1',
                'email' => 'test1@test.com',
                'password' => 'secret',
                'role' => 'customer',
                'address' => '1 A street',
                'phone_number' => '123456789',
                'license_number' => '987654321',
                'status' => false,
                'cc_name' => 'testCustomer card name',
                'number' => '0000111122223333',
                'exp_date' => '08/20',
            ]
        );

        $response->assertStatus(200);

        // 2 - with existing username
        $response = $this->call('POST', 'api/customers',
            [
                'name' => 'testCustomer2',
                'email' => 'test2@test.com',
                'password' => 'secret',
                'role' => 'customer',
                'address' => '1 A street',
                'phone_number' => '123456789',
                'license_number' => '987654321',
                'status' => false,
                'cc_name' => 'testCustomer card name',
                'number' => '0000111122223333',
                'exp_date' => '08/20',
            ]
        );

        $response->assertStatus(422);
    }
}
