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
        ]);
    }
}
