<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkReportEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_report_entries')->insert([
            [
                'work_report_id' => 1,
                'order' => 1,
                'service_type' => 'App\\Models\\ContractService',
                'service_id' => 1,
                'quantity' => 150.00,
                'total' => 6825.00,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'work_report_id' => 1,
                'order' => 2,
                'service_type' => 'App\\Models\\ContractService',
                'service_id' => 2,
                'quantity' => 45.00,
                'total' => 5400.00,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'work_report_id' => 1,
                'order' => 3,
                'service_type' => 'App\\Models\\ContractExtraService',
                'service_id' => 1,
                'quantity' => 2.00,
                'total' => 2500.00,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'work_report_id' => 2,
                'order' => 1,
                'service_type' => 'App\\Models\\ContractService',
                'service_id' => 3,
                'quantity' => 200.00,
                'total' => 17150.00,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'work_report_id' => 2,
                'order' => 2,
                'service_type' => 'App\\Models\\ContractExtraService',
                'service_id' => 2,
                'quantity' => 80.00,
                'total' => 36000.00,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'work_report_id' => 3,
                'order' => 1,
                'service_type' => 'App\\Models\\ContractExtraService',
                'service_id' => 3,
                'quantity' => 1.00,
                'total' => 85000.00,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ]
        ]);
    }
}
