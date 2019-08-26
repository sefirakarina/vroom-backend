<?php

namespace Tests\Feature;

use App\Customer;
use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminTest extends TestCase
{
    use DatabaseTransactions;

    public function testExample()
    {

        factory(User::class)->create([
            'id' => 2,
            'name' => "john",
            'email' => 'john@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'SuperAdmin'
        ]);

        $login = $this->call('POST', 'api/auth/login',
            [
                'email' => 'john@gmail.com',
                'password' => 'secret',
            ]
        );
        $login->assertStatus(200);

        // registration test
        // 1 - with non-existing email
        $response = $this->call('POST', 'api/users',
            [
                'name' => 'test1',
                'email' => 'test1@test.com',
                'password' => Hash::make("secret"),
                'role' => 'admin'
            ]
        );
        $response->assertStatus(200);

        // 2 - with existing email
        $response = $this->call('POST', 'api/users',
            [
                'name' => 'test2',
                'email' => 'test1@test.com',
                'password' => Hash::make("secret"),
                'role' => 'admin',

            ]
        );
        $response->assertStatus(422);

        $response = $this->call('GET', 'api/users',
            $this->transformHeadersToServerVars(['Authorization' => $login->json("access_token")])
        );
        $response->assertStatus(200);

        $user = User::users();
        $this->assertCount(2, $user);
        $response->assertStatus(200);
    }
}
