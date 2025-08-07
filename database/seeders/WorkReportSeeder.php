<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_reports')->insert([
            [
                'contract_id' => 1,
                'written_by' => 1,
                'report_month' => 'martie',
                'report_year' => 2025,
                'report_number' => 1,
                'observations' => 'Lucrările au progresat conform programării. Toate materialele sunt conform specificațiilor.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'contract_id' => 1,
                'written_by' => 1,
                'report_month' => 'aprilie',
                'report_year' => 2025,
                'report_number' => 2,
                'observations' => 'Finalizare lucrări de finisaj. Sistemul de lift a fost instalat cu succes.',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'contract_id' => 1,
                'written_by' => 1,
                'report_month' => 'mai',
                'report_year' => 2025,
                'report_number' => 3,
                'observations' => 'Lucrări de amenajare exterioară în curs de desfășurare.',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ]
        ]);
    }
}
