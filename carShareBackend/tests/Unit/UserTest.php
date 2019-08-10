<?php
namespace Tests\Unit;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
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

        $users = User::users();
        $this->assertCount(2, $users);

        $this->assertEquals([
            [
                'id' => 1,
                'name' => "Sue",
                'email' => 'Sue@gmail.com',
                'role' => 'customer'
            ],
            [
                'id' => 2,
                'name' => "john",
                'email' => 'john@gmail.com',
                'role' => 'admin'
            ],
        ], $users->toArray());
    }
}