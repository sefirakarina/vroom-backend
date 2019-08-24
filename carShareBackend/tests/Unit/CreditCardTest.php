<?php
namespace Tests\Unit;
use App\Customer;
use App\Role;
use App\User;
use App\CreditCard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreditCardTest extends TestCase
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
            'id' => 1000,
            'name' => "Sue",
            'email' => 'Sue@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'customer'
        ]);

        factory(User::class)->create([
            'id' => 2000,
            'name' => "john",
            'email' => 'john@gmail.com',
            'password' => Hash::make("secret"),
            'role' => 'admin'
        ]);

        factory(Customer::class)->create([
            'id' => 1000,
            'user_id' => 1000,
            'address' => "P Sherman 42 Wallaby Way, Sydney",
            'phone_number' => "04010204",
            'license_number' =>"123456",
            'status'=> true
        ]);

        factory(Customer::class)->create([
            'id' => 2000,
            'user_id' => 2000,
            'address' => "123 something road",
            'phone_number' => "044345525",
            'license_number' =>"324134",
            'status'=> false
        ]);

        factory(CreditCard::class)->create([
            'id' => 1000,
            'customer_id' => 1000,
            'number' => "1111222233334444",
            'name' => "sue",
            'exp_date' =>"12/20"
        ]);

        factory(CreditCard::class)->create([
            'id' => 2000,
            'customer_id' => 2000,
            'number' => "5555666677778888",
            'name' => "john",
            'exp_date' =>"11/21"
        ]);


        $creditCars = CreditCard::creditCard();
        $this->assertCount(2, $creditCars);

        $this->assertEquals([
            [
                'id' => 1000,
                'customer_id' => 1000,
                'number' => "1111222233334444",
                'name' => "sue",
                'exp_date' =>"12/20"
            ],
            [
                'id' => 2000,
                'customer_id' => 2000,
                'number' => "5555666677778888",
                'name' => "john",
                'exp_date' =>"11/21"
            ],
        ], $creditCars->toArray());
    }
}