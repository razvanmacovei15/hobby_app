<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'random@email.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'dan',
                'last_name' => 'boitos',
                'email' => 'dan@email.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'razvan',
                'last_name' => 'macovei',
                'email' => 'razvanmc15@gmail.com',
                'password' => Hash::make('Cj159550285/'),
            ],
        ]);
    }
}
