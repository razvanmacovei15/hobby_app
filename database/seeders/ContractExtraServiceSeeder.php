<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractExtraServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contract_extra_services')->insert([
            [
                'contract_id' => 1,
                'company_id' => 1,
                'name' => 'Instalare sistem de ventilatie',
                'unit_of_measure' => 'buc',
                'price_per_unit_of_measure' => 1250.00,
                'quantity' => 4,
                'description' => 'Instalare sistem de ventilatie pentru toate apartamentele',
                'provided_at' => '2025-03-15',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'contract_id' => 1,
                'company_id' => 2,
                'name' => 'Montare geamuri termopan',
                'unit_of_measure' => 'mp',
                'price_per_unit_of_measure' => 450.00,
                'quantity' => 120,
                'description' => 'Montare geamuri termopan pentru toate ferestrele',
                'provided_at' => '2025-04-10',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'contract_id' => 1,
                'company_id' => 1,
                'name' => 'Instalare sistem de lift',
                'unit_of_measure' => 'buc',
                'price_per_unit_of_measure' => 85000.00,
                'quantity' => 2,
                'description' => 'Instalare si montare sisteme de lift pentru ambele scari',
                'provided_at' => '2025-05-20',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]
        ]);
    }
}
