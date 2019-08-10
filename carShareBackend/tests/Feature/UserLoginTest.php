<?php
namespace Tests\Feature;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
class UserLoginTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {

        factory(User::class)->create([
            'id' => 1,
            'name' => "Sue",
            'email' => 'Sue@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'customer'
        ]);

        factory(User::class)->create([
            'id' => 2,
            'name' => "john",
            'email' => 'john@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'admin'
        ]);

        factory(User::class)->create([
            'id' => 3,
            'name' => "jane",
            'email' => 'jane@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'superAdmin'
        ]);

        //customer login test
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'Sue@gmail.com',
                'password' => 'secret',
            ]
        );
        $response->assertStatus(200);

        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'Sue@gmail.com',
                'password' => Hash::make('wrongpassword'),
            ]
        );
        $response->assertStatus(401);

        //admin login test
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'john@gmail.com',
                'password' => 'secret',
            ]
        );
        $response->assertStatus(200);
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'john@gmail.com',
                'password' => Hash::make('wrongpassword'),
            ]
        );
        $response->assertStatus(401);

        //superAdmin login test
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'jane@gmail.com',
                'password' => 'secret',
            ]
        );
        $response->assertStatus(200);
//        $array = json_decode($response->getContent());
//        var_dump($array->access_token);
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => 'jane@gmail.com',
                'password' => Hash::make('wrongpassword'),
            ]
        );
        $response->assertStatus(401);
        //no username and pasword filled
        $response = $this->call('POST', 'api/auth/login',
            [
                'email' => '',
                'password' => Hash::make(''),
            ]
        );
        $response->assertStatus(401);
    }
}