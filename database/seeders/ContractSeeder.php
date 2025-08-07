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
        DB::table('contracts')->insert([
            [
                'contract_number' => 'nr.229/01.04.2025',
                'beneficiary_id' => 1,
                'executor_id' => 2,
                'start_date' => '2024-01-15',
                'end_date' => '2024-12-31',
                'sign_date' => '2025-04-01',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ]
        ]);
    }
}
