<?php

namespace Database\Seeders;

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
                'name' => 'Complex Comercial Plaza Mall',
                'owner_id' => 2, // Building Solutions Ltd
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'name' => 'Bloc de Locuințe Green Valley',
                'owner_id' => 1, // Construct Pro SRL
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ]
        ]);

        // Add some workspace executors (companies that work on these workspaces)
        DB::table('workspace_executors')->insert([
            [
                'workspace_id' => 1,
                'executor_id' => 2, // Building Solutions Ltd as executor
                'is_active' => true,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'workspace_id' => 2,
                'executor_id' => 1, // Construct Pro SRL as executor
                'is_active' => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'workspace_id' => 3,
                'executor_id' => 2, // Building Solutions Ltd as executor
                'is_active' => true,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ]
        ]);
    }
}
