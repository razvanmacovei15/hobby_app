<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contracts')->upsert([
            [
                'registration_key' => 'nr.229/01.04.2025',
                'contract_number' => '229',
                'beneficiary_id' => 1,
                'executor_id' => 2,
                'start_date' => '2024-01-15',
                'end_date' => '2024-12-31',
                'sign_date' => '2025-04-01',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'registration_key' => 'nr.230/05.04.2025',
                'contract_number' => '230',
                'beneficiary_id' => 1, // Construct Pro SRL (workspace owner)
                'executor_id' => 3, // ElectricTech Solutions SRL
                'start_date' => '2025-04-01',
                'end_date' => '2025-12-31',
                'sign_date' => '2025-04-05',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'registration_key' => 'nr.232/07.04.2025',
                'contract_number' => '232',
                'beneficiary_id' => 1, // Construct Pro SRL (workspace owner)
                'executor_id' => 5, // Interior Design Pro SRL
                'start_date' => '2025-04-01',
                'end_date' => '2026-03-31',
                'sign_date' => '2025-04-07',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'registration_key' => 'nr.233/08.04.2025',
                'contract_number' => '233',
                'beneficiary_id' => 1, // Construct Pro SRL (workspace owner)
                'executor_id' => 6, // Roofing Specialists SRL
                'start_date' => '2025-04-08',
                'end_date' => '2025-10-31',
                'sign_date' => '2025-04-08',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'registration_key' => 'nr.234/09.04.2025',
                'contract_number' => '234',
                'beneficiary_id' => 1, // Construct Pro SRL (workspace owner)
                'executor_id' => 7, // Demolition Experts SRL
                'start_date' => '2025-04-09',
                'end_date' => '2025-06-30',
                'sign_date' => '2025-04-09',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'registration_key' => 'nr.235/10.04.2025',
                'contract_number' => '235',
                'beneficiary_id' => 1, // Construct Pro SRL (workspace owner)
                'executor_id' => 8, // Concrete Works SRL
                'start_date' => '2025-04-10',
                'end_date' => '2025-09-30',
                'sign_date' => '2025-04-10',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ], ['registration_key'], ['contract_number', 'beneficiary_id', 'executor_id', 'start_date', 'end_date', 'sign_date', 'updated_at']);
    }
}
