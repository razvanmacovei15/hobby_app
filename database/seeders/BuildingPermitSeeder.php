<?php

namespace Database\Seeders;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingPermitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('building_permits')->insert([
            [
                'permit_number' => 'BP-2024-000001',
                'permit_type' => PermitType::CONSTRUCTION->value,
                'status' => PermitStatus::APPROVED->value,
                'workspace_id' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'permit_number' => 'BP-2024-000002',
                'permit_type' => PermitType::RENOVATION->value,
                'status' => PermitStatus::PENDING->value,
                'workspace_id' => 2,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'permit_number' => 'BP-2024-000003',
                'permit_type' => PermitType::EXTENSION->value,
                'status' => PermitStatus::APPROVED->value,
                'workspace_id' => 3,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ]);
    }
}
