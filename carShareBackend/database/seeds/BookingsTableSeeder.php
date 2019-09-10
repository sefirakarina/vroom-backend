<?php

use Illuminate\Database\Seeder;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bookings')->insert([
            'id' => 1,
            'customer_id' => 2,
            'car_id' => 6,
            'return_location_id' => 1,
            'begin_time' => new DateTime('2019-09-27 14:30:12'),
            'return_time' =>new DateTime('2019-09-28 12:30:12')
        ]);

        DB::table('bookings')->insert([
            'id' => 2,
            'customer_id' => 3,
            'car_id' => 7,
            'return_location_id' => 2,
            'begin_time' => new DateTime('2019-09-10 12:30:12'),
            'return_time' =>new DateTime('2019-11-17 10:30:12')
        ]);
    }
}
