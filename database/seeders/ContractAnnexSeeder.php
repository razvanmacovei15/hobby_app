<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractAnnexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contract_annexes')->insert([
            [
                'contract_id' => 1,
                'annex_number' => 1,
                'sign_date' => '2025-02-15',
                'notes' => 'Modificare prețuri: Actualizare costuri materiale conform indicilor de preț',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]
        ]);
    }
}
