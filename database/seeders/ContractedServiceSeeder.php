<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractedServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contracted_services')->insert([
            [
                'contract_annex_id' => 1,
                'sort_order' => 1,
                'name' => 'Excavare fundații',
                'unit_of_measure' => 'm³',
                'price_per_unit_of_measure' => 45.50,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'contract_annex_id' => 1,
                'sort_order' => 2,
                'name' => 'Betonare fundații',
                'unit_of_measure' => 'm³',
                'price_per_unit_of_measure' => 120.00,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'contract_annex_id' => 1,
                'sort_order' => 3,
                'name' => 'Zidărie pereți',
                'unit_of_measure' => 'm²',
                'price_per_unit_of_measure' => 85.75,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]
        ]);
    }
}
