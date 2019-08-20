<?php
namespace Tests\Unit;
use App\Customer;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerTest extends TestCase
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

        factory(Customer::class)->create([
            'id' => 1,
            'user_id' => 1,
            'address' => "P Sherman 42 Wallaby Way, Sydney",
            'phone_number' => "04010204",
            'license_number' =>"123456",
            'status'=> true
        ]);

        factory(Customer::class)->create([
            'id' => 2,
            'user_id' => 2,
            'address' => "123 something road",
            'phone_number' => "044345525",
            'license_number' =>"324134",
            'status'=> false
        ]);

        $customers = Customer::customer();
        $this->assertCount(2, $customers);

        $this->assertEquals([
            [
                'id' => 1,
                'user_id' => 1,
                'address' => "P Sherman 42 Wallaby Way, Sydney",
                'phone_number' => "04010204",
                'license_number' =>"123456",
                'status'=> true
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'address' => "123 something road",
                'phone_number' => "044345525",
                'license_number' =>"324134",
                'status'=> false
            ],
        ], $customers->toArray());


    }
}
