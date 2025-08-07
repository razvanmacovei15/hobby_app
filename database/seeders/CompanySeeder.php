<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            [
                'name' => 'Construct Pro SRL',
                'j' => 'J12345678/2023/123',
                'cui' => 'RO12345678',
                'place_of_registration' => 'Cluj-Napoca',
                'iban' => 'RO1234567890123456789012',
                'representative_id' => 1,
                'phone' => '+40 264 123 456',
                'address_id' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'name' => 'Building Solutions Ltd',
                'j' => 'J87654321/2023/456',
                'cui' => 'RO87654321',
                'place_of_registration' => 'Bucuresti',
                'iban' => 'RO8765432109876543210987',
                'representative_id' => 1,
                'phone' => '+40 21 987 654',
                'address_id' => 2,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ]
        ]);
    }
}
