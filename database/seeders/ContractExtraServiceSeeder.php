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
        DB::table('work_report_extra_services')->insert([
            [
                'work_report_id' => 1,
                'contract_id' => 1,
                'executor_company_id' => 2,
                'beneficiary_company_id' => 1,
                'name' => 'Instalare sistem de ventilatie',
                'unit_of_measure' => 'buc',
                'price_per_unit_of_measure' => 1250.00,
                'notes' => 'Instalare sistem de ventilatie pentru toate apartamentele',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'work_report_id' => 1,
                'contract_id' => 1,
                'executor_company_id' => 2,
                'beneficiary_company_id' => 1,
                'name' => 'Montare geamuri termopan',
                'unit_of_measure' => 'mp',
                'price_per_unit_of_measure' => 450.00,
                'notes' => 'Montare geamuri termopan pentru toate ferestrele',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'work_report_id' => 2,
                'contract_id' => 1,
                'executor_company_id' => 2,
                'beneficiary_company_id' => 1,
                'name' => 'Instalare sistem de lift',
                'unit_of_measure' => 'buc',
                'price_per_unit_of_measure' => 85000.00,
                'notes' => 'Instalare si montare sisteme de lift pentru ambele scari',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]
        ]);
    }
}
