<?php

namespace Database\Seeders;

use App\Enums\ExecutorType;
use App\Models\WorkspaceExecutor;
use Database\Factories\WorkspaceExecutorEngineerFactory;
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
                'is_active' => false,
                'has_contract' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'workspace_id' => 2,
                'executor_id' => 4, // Plumbing Masters SRL
                'executor_type' => ExecutorType::PLUMBING->value,
                'is_active' => false,
                'has_contract' => false,
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
            // Additional executors for workspace 1
            [
                'workspace_id' => 1,
                'executor_id' => 6, // Roofing Specialists SRL
                'executor_type' => ExecutorType::STRUCTURAL->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'workspace_id' => 1,
                'executor_id' => 7, // Demolition Experts SRL
                'executor_type' => ExecutorType::DEMOLITION->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(24),
                'updated_at' => now()->subDays(24),
            ],
            [
                'workspace_id' => 1,
                'executor_id' => 8, // Concrete Works SRL
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
                'executor_type' => ExecutorType::STRUCTURAL->value,
                'is_active' => false,
                'has_contract' => false,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'workspace_id' => 3,
                'executor_id' => 10, // Insulation Masters SRL
                'executor_type' => ExecutorType::INSULATION->value,
                'is_active' => false,
                'has_contract' => false,
                'created_at' => now()->subDays(14),
                'updated_at' => now()->subDays(14),
            ],
            [
                'workspace_id' => 3,
                'executor_id' => 3, // ElectricTech Solutions SRL
                'executor_type' => ExecutorType::ELECTRICAL->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(13),
                'updated_at' => now()->subDays(13),
            ],
            [
                'workspace_id' => 3,
                'executor_id' => 4, // Plumbing Masters SRL
                'executor_type' => ExecutorType::PLUMBING->value,
                'is_active' => true,
                'has_contract' => true,
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
        ], ['workspace_id', 'executor_id'], ['executor_type', 'is_active', 'has_contract', 'updated_at']);

        // Assign engineers to workspace executors
        $this->assignEngineersToExecutors();
    }

    private function assignEngineersToExecutors(): void
    {
        $factory = new WorkspaceExecutorEngineerFactory;

        // Get all workspace executors with their workspace relationships
        $executors = WorkspaceExecutor::with('workspace.users')->get();

        foreach ($executors as $executor) {
            $workspaceUsers = $executor->workspace->users;

            if ($workspaceUsers->count() === 0) {
                continue;
            }

            // Assign 1-3 random engineers to each executor
            $numberOfEngineers = fake()->numberBetween(1, min(3, $workspaceUsers->count()));
            $selectedUsers = $workspaceUsers->random($numberOfEngineers);

            foreach ($selectedUsers as $user) {
                // Check if this assignment already exists
                $exists = DB::table('workspace_executor_engineers')
                    ->where('workspace_executor_id', $executor->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if (! $exists) {
                    DB::table('workspace_executor_engineers')->insert([
                        'workspace_executor_id' => $executor->id,
                        'user_id' => $user->id,
                        'assigned_at' => fake()->dateTimeBetween('-1 month', 'now'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
