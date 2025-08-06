<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            [
                'name' => 'Timisoara',
                'address' => 'str. Torontarului',
                'city' => 'Timisoara',
                'state' => 'Timisoara',
                'country' => 'Romania',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'name' => 'Grand Park Sud',
                'address' => 'str. Buna Ziua',
                'city' => 'Cluj-Napoca',
                'state' => 'Cluj',
                'country' => 'Romania',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'name' => 'Elite-City',
                'address' => 'bld. Muncii, nr. 4-6',
                'city' => 'Cluj-Napoca',
                'state' => 'Cluj',
                'country' => 'Romania',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
        ]);
    }
}
