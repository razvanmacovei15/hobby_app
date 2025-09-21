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
            ],
            [
                'contract_id' => 2, // ElectricTech Solutions SRL
                'annex_number' => 1,
                'sign_date' => '2025-04-20',
                'notes' => 'Adăugare servicii electrice suplimentare: Sistem de iluminat inteligent',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'contract_id' => 3, // Plumbing Masters SRL
                'annex_number' => 1,
                'sign_date' => '2025-04-22',
                'notes' => 'Extindere lucrări instalații sanitare: Sistem de încălzire în pardoseală',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'contract_id' => 4, // Interior Design Pro SRL
                'annex_number' => 1,
                'sign_date' => '2025-04-25',
                'notes' => 'Modificare specificații finisaje: Materiale premium pentru zonele comune',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]
        ]);
    }
}
