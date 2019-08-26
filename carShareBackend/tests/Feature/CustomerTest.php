<?php

namespace Tests\Feature;

use App\Customer;
use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerTest extends TestCase
{
    use DatabaseTransactions;

    public function testExample()
    {

        factory(User::class)->create([
            'id' => 2,
            'name' => "john",
            'email' => 'john@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'admin'
        ]);

        $login = $this->call('POST', 'api/auth/login',
            [
                'email' => 'john@gmail.com',
                'password' => 'secret',
            ]
        );
        $login->assertStatus(200);

        // customer registration test
        // 1 - with non-existing email
        $response = $this->call('POST', 'api/customers',
            [
                'name' => 'testCustomer1',
                'email' => 'test1@test.com',
                'password' => Hash::make("secret"),
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

        // 2 - with existing email
        $response = $this->call('POST', 'api/customers',
            [
                'name' => 'testCustomer2',
                'email' => 'test2@test.com',
                'password' => Hash::make("secret"),
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

        $response = $this->call('GET', 'api/customers',
            $this->transformHeadersToServerVars(['Authorization' => $login->json("access_token")])
        );
        $response->assertStatus(200);

        $customer = Customer::customer();
        $this->assertCount(1, $customer);
        $response->assertStatus(200);
    }
}
