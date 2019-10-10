<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => "super1",
            'email' => "super@super.com",
            'password' => Hash::make("secret"),
            'role' =>"superAdmin"
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => "admin1",
            'email' => "admin@admin.com",
            'password' => Hash::make("secret"),
            'role' =>"admin"
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'name' => "john doe",
            'email' => "john@doe.com",
            'password' => Hash::make("secret"),
            'role' =>"customer"
        ]);

        DB::table('users')->insert([
            'id' => 4,
            'name' => "jane doe",
            'email' => "jane@doe.com",
            'password' => Hash::make("secret"),
            'role' =>"customer"
        ]);

        DB::table('users')->insert([
            'id' => 5,
            'name' => "someone",
            'email' => "a@a.com",
            'password' => Hash::make("secret"),
            'role' =>"customer"
        ]);
    }
}
