<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('addresses')->insert([
            [
                'street' => 'Muncii',
                'street_number' => '4-6',
                'city' => 'Cluj-Napoca',
                'state' => 'Cluj',
                'country' => 'Romania',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'street' => 'Buna Ziua',
                'street_number' => '46',
                'city' => 'Cluj-Napoca',
                'state' => 'Cluj',
                'country' => 'Romania',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'street' => 'Torontarului',
                'street_number' => '26',
                'city' => 'Timisoara',
                'state' => 'Timisoara',
                'country' => 'Romania',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
        ]);
    }
}
