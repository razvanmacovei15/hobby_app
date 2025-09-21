<?php

namespace Database\Seeders;

use App\Enums\ExecutorType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkspaceExecutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add some workspace executors (companies that work on these workspaces)
        DB::table('workspace_executors')->upsert([
            [
                'workspace_id' => 1,
                'executor_id' => 2, // Building Solutions Ltd as executor
                'responsible_engineer_id' => 2, // Dan Boitos as responsible engineer
                'executor_type' => ExecutorType::ELECTRICAL->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            // New workspace executors for workspace 2
            [
                'workspace_id' => 2,
                'executor_id' => 3, // ElectricTech Solutions SRL
                'responsible_engineer_id' => 3, // Alice Johnson as responsible engineer
                'executor_type' => ExecutorType::ELECTRICAL->value,
                'is_active' => false,
                'has_contract' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'workspace_id' => 2,
                'executor_id' => 4, // Plumbing Masters SRL
                'responsible_engineer_id' => null, // No responsible engineer assigned
                'executor_type' => ExecutorType::PLUMBING->value,
                'is_active' => false,
                'has_contract' => false,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'workspace_id' => 2,
                'executor_id' => 5, // Interior Design Pro SRL
                'responsible_engineer_id' => 4, // Bob Smith as responsible engineer
                'executor_type' => ExecutorType::FINISHES->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            // Additional executors for workspace 1
            [
                'workspace_id' => 1,
                'executor_id' => 6, // Roofing Specialists SRL
                'responsible_engineer_id' => 5, // Carol Davis as responsible engineer
                'executor_type' => ExecutorType::STRUCTURAL->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'workspace_id' => 1,
                'executor_id' => 7, // Demolition Experts SRL
                'responsible_engineer_id' => 6, // David Wilson as responsible engineer
                'executor_type' => ExecutorType::DEMOLITION->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(24),
                'updated_at' => now()->subDays(24),
            ],
            [
                'workspace_id' => 1,
                'executor_id' => 8, // Concrete Works SRL
                'responsible_engineer_id' => 2, // Dan Boitos (same engineer for multiple executors)
                'executor_type' => ExecutorType::STRUCTURAL->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(23),
                'updated_at' => now()->subDays(23),
            ],
            // Executors for workspace 3 (no contracts)
            [
                'workspace_id' => 3,
                'executor_id' => 9, // Steel Structures SRL
                'responsible_engineer_id' => null, // No responsible engineer assigned
                'executor_type' => ExecutorType::STRUCTURAL->value,
                'is_active' => false,
                'has_contract' => false,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'workspace_id' => 3,
                'executor_id' => 10, // Insulation Masters SRL
                'responsible_engineer_id' => 7, // Elena Rodriguez as responsible engineer
                'executor_type' => ExecutorType::INSULATION->value,
                'is_active' => false,
                'has_contract' => false,
                'created_at' => now()->subDays(14),
                'updated_at' => now()->subDays(14),
            ],
            [
                'workspace_id' => 3,
                'executor_id' => 3, // ElectricTech Solutions SRL
                'responsible_engineer_id' => 8, // Frank Miller as responsible engineer
                'executor_type' => ExecutorType::ELECTRICAL->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(13),
                'updated_at' => now()->subDays(13),
            ],
            [
                'workspace_id' => 3,
                'executor_id' => 4, // Plumbing Masters SRL
                'responsible_engineer_id' => 9, // Grace Taylor as responsible engineer
                'executor_type' => ExecutorType::PLUMBING->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
        ], ['workspace_id', 'executor_id'], ['executor_type', 'is_active', 'has_contract', 'responsible_engineer_id', 'updated_at']);
    }
}
