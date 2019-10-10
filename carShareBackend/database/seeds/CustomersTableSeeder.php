<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert([
            'id' => 1,
            'user_id' => 3,
            'address' => "P Sherman 42 Wallaby Way, Sydney",
            'phone_number' => "04010204",
            'license_number' =>"123456",
            'status'=> true
        ]);

        DB::table('customers')->insert([
            'id' => 2,
            'user_id' => 4,
            'address' => "123 something road",
            'phone_number' => "044345525",
            'license_number' =>"324134",
            'status'=> false
        ]);

        DB::table('customers')->insert([
            'id' => 3,
            'user_id' => 5,
            'address' => "100 success road",
            'phone_number' => "04453214",
            'license_number' =>"531413",
            'status'=> true
        ]);

        DB::table('credit_cards')->insert([
            'id' => 1,
            'customer_id' => 1,
            'number' => "1234567812345678",
            'name' => "john doe",
            'exp_date' =>"10/20"
        ]);

        DB::table('credit_cards')->insert([
            'id' => 2,
            'customer_id' => 2,
            'number' => "1234567867890123",
            'name' => "jane doe",
            'exp_date' =>"11/21"
        ]);

        DB::table('credit_cards')->insert([
            'id' => 3,
            'customer_id' => 3,
            'number' => "1234567834567890",
            'name' => "someone",
            'exp_date' =>"12/22"
        ]);


    }
}
