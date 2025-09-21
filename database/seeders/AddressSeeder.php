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
                'building' => 'J8',
                'apartment_number' => '135',
                'state' => 'Cluj',
                'country' => 'Romania',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'street' => 'Buna Ziua',
                'street_number' => '46',
                'city' => 'Cluj-Napoca',
                'building' => 'J8',
                'apartment_number' => '135',
                'state' => 'Cluj',
                'country' => 'Romania',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'street' => 'Torontarului',
                'street_number' => '26',
                'city' => 'Timisoara',
                'building' => 'J8',
                'apartment_number' => '135',
                'state' => 'Timisoara',
                'country' => 'Romania',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],[
                'street' => 'Strada Pacii',
                'street_number' => '15',
                'city' => 'Iasi',
                'building' => 'A2',
                'apartment_number' => '45',
                'state' => 'Iasi',
                'country' => 'Romania',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],[
                'street' => 'Bulevardul Tomis',
                'street_number' => '128',
                'city' => 'Constanta',
                'building' => 'C1',
                'apartment_number' => '72',
                'state' => 'Constanta',
                'country' => 'Romania',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ]);
    }
}
