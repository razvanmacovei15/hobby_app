<?php

namespace Database\Seeders;

use App\Enums\ExecutorType;
use App\Enums\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkspaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('workspaces')->insert([
            [
                'name' => 'Proiect Rezidențial Central Park',
                'owner_id' => 1, // Construct Pro SRL
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'name' => 'Proiect Comercial Plaza Business',
                'owner_id' => 1, // Construct Pro SRL (same owner as workspace 1)
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'name' => 'Elite City',
                'owner_id' => 1, // Construct Pro SRL (same owner as workspace 1)
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
        ]);

        // Add some workspace executors (companies that work on these workspaces)
        DB::table('workspace_executors')->insert([
            [
                'workspace_id' => 1,
                'executor_id' => 2, // Building Solutions Ltd as executor
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
                'executor_type' => ExecutorType::ELECTRICAL->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'workspace_id' => 2,
                'executor_id' => 4, // Plumbing Masters SRL
                'executor_type' => ExecutorType::PLUMBING->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'workspace_id' => 2,
                'executor_id' => 5, // Interior Design Pro SRL
                'executor_type' => ExecutorType::FINISHES->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ]);
    }
}
