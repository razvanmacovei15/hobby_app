<?php

namespace Database\Seeders;

use App\Enums\WorkReportStatus;
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
                'workspace_id' => 1,
                'beneficiary_id' => 1,
                'executor_id' => 2,
                'written_by' => 1,
                'report_number' => 1,
                'report_month' => 3,
                'report_year' => 2025,
                'notes' => 'Lucrările au progresat conform programării. Toate materialele sunt conform specificațiilor.',
                'status' => WorkReportStatus::APPROVED->value,
                'approved_at' => now()->subDays(4),
                'approved_by' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'contract_id' => 1,
                'workspace_id' => 1,
                'beneficiary_id' => 1,
                'executor_id' => 2,
                'written_by' => 1,
                'report_number' => 2,
                'report_month' => 4,
                'report_year' => 2025,
                'notes' => 'Finalizare lucrări de finisaj. Sistemul de lift a fost instalat cu succes.',
                'status' => WorkReportStatus::PENDING_APPROVAL->value,
                'approved_at' => null,
                'approved_by' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'contract_id' => 1,
                'workspace_id' => 1,
                'beneficiary_id' => 1,
                'executor_id' => 2,
                'written_by' => 1,
                'report_number' => 3,
                'report_month' => 5,
                'report_year' => 2025,
                'notes' => 'Lucrări de amenajare exterioară în curs de desfășurare.',
                'status' => WorkReportStatus::DRAFT->value,
                'approved_at' => null,
                'approved_by' => null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ]
        ]);
    }
}
