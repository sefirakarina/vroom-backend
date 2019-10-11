<?php

namespace Tests\Feature;


use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use DatabaseTransactions;

    /* 24. As a super-admin, I want to be able to delete existing admin */
    public function testExample(){
        // Create super-admin
        factory(User::class)->create([
            'id' => 1,
            'name' => "john",
            'email' => 'john@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'SuperAdmin'
        ]);

        // Create admin
        factory(User::class)->create([
            'id' => 2,
            'name' => "admin",
            'email' => 'admin@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'admin'
        ]);

        // Login as super-admin
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'john@gmail.com',
                'password' => 'secret',
            ]
        );
        $response->assertStatus(200);

        // Delete admin
        $response = $this->call('DELETE', 'api/users/2');
        $response->assertStatus(200);
    }

}
