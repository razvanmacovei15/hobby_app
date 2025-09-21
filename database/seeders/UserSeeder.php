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
                'first_name' => 'Maco',
                'last_name' => 'Support',
                'email' => 'support@macocoding.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Dan',
                'last_name' => 'Boitos',
                'email' => 'dan@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Bob',
                'last_name' => 'Smith',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Carol',
                'last_name' => 'Davis',
                'email' => 'carol@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'David',
                'last_name' => 'Wilson',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Elena',
                'last_name' => 'Rodriguez',
                'email' => 'elena@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Frank',
                'last_name' => 'Miller',
                'email' => 'frank@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Grace',
                'last_name' => 'Taylor',
                'email' => 'grace@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Henry',
                'last_name' => 'Anderson',
                'email' => 'henry@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Isabella',
                'last_name' => 'Brown',
                'email' => 'isabella@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Jack',
                'last_name' => 'Thompson',
                'email' => 'jack@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Katherine',
                'last_name' => 'White',
                'email' => 'katherine@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Liam',
                'last_name' => 'Garcia',
                'email' => 'liam@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Maya',
                'last_name' => 'Martinez',
                'email' => 'maya@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Nathan',
                'last_name' => 'Lee',
                'email' => 'nathan@example.com',
                'password' => Hash::make('password'),
            ],[
                'first_name' => 'Olivia',
                'last_name' => 'Clark',
                'email' => 'olivia@example.com',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
