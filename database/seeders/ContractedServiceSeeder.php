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
            // Annex 1 - Building Solutions Ltd (5 services)
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
            ],
            [
                'contract_annex_id' => 1,
                'sort_order' => 4,
                'name' => 'Montare placă acoperiș',
                'unit_of_measure' => 'm²',
                'price_per_unit_of_measure' => 95.00,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'contract_annex_id' => 1,
                'sort_order' => 5,
                'name' => 'Finisaje exterioare',
                'unit_of_measure' => 'm²',
                'price_per_unit_of_measure' => 75.25,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            
            // Annex 2 - ElectricTech Solutions SRL (5 services)
            [
                'contract_annex_id' => 2,
                'sort_order' => 1,
                'name' => 'Instalații electrice generale',
                'unit_of_measure' => 'm',
                'price_per_unit_of_measure' => 25.50,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'contract_annex_id' => 2,
                'sort_order' => 2,
                'name' => 'Tablouri electrice',
                'unit_of_measure' => 'buc',
                'price_per_unit_of_measure' => 450.00,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'contract_annex_id' => 2,
                'sort_order' => 3,
                'name' => 'Sistem iluminat inteligent',
                'unit_of_measure' => 'punct',
                'price_per_unit_of_measure' => 125.00,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'contract_annex_id' => 2,
                'sort_order' => 4,
                'name' => 'Prize și întrerupătoare',
                'unit_of_measure' => 'buc',
                'price_per_unit_of_measure' => 35.75,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'contract_annex_id' => 2,
                'sort_order' => 5,
                'name' => 'Sistem video-interfon',
                'unit_of_measure' => 'set',
                'price_per_unit_of_measure' => 890.00,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            
            // Annex 3 - Plumbing Masters SRL (5 services)
            [
                'contract_annex_id' => 3,
                'sort_order' => 1,
                'name' => 'Instalații apă rece',
                'unit_of_measure' => 'm',
                'price_per_unit_of_measure' => 18.50,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'contract_annex_id' => 3,
                'sort_order' => 2,
                'name' => 'Instalații apă caldă',
                'unit_of_measure' => 'm',
                'price_per_unit_of_measure' => 22.75,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'contract_annex_id' => 3,
                'sort_order' => 3,
                'name' => 'Sistem încălzire în pardoseală',
                'unit_of_measure' => 'm²',
                'price_per_unit_of_measure' => 85.00,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'contract_annex_id' => 3,
                'sort_order' => 4,
                'name' => 'Instalații canalizare',
                'unit_of_measure' => 'm',
                'price_per_unit_of_measure' => 28.90,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'contract_annex_id' => 3,
                'sort_order' => 5,
                'name' => 'Obiecte sanitare premium',
                'unit_of_measure' => 'set',
                'price_per_unit_of_measure' => 1250.00,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            
            // Annex 4 - Interior Design Pro SRL (5 services)
            [
                'contract_annex_id' => 4,
                'sort_order' => 1,
                'name' => 'Finisaje pereți interiori',
                'unit_of_measure' => 'm²',
                'price_per_unit_of_measure' => 45.00,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'contract_annex_id' => 4,
                'sort_order' => 2,
                'name' => 'Pardoseli premium',
                'unit_of_measure' => 'm²',
                'price_per_unit_of_measure' => 125.50,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'contract_annex_id' => 4,
                'sort_order' => 3,
                'name' => 'Tavan fals decorativ',
                'unit_of_measure' => 'm²',
                'price_per_unit_of_measure' => 65.75,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'contract_annex_id' => 4,
                'sort_order' => 4,
                'name' => 'Mobilier integrat bucătărie',
                'unit_of_measure' => 'set',
                'price_per_unit_of_measure' => 3500.00,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'contract_annex_id' => 4,
                'sort_order' => 5,
                'name' => 'Sistem climatizare',
                'unit_of_measure' => 'unitate',
                'price_per_unit_of_measure' => 2800.00,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]
        ]);
    }
}
