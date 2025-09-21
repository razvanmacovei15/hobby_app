<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkspaceUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assign users to workspaces
        DB::table('workspace_users')->upsert([
            // Workspace 1 users with is_default = true
            [
                'user_id' => 1, // admin user
                'workspace_id' => 1,
                'is_default' => true,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'user_id' => 2,
                'workspace_id' => 1,
                'is_default' => true,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'user_id' => 3,
                'workspace_id' => 1,
                'is_default' => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'user_id' => 4,
                'workspace_id' => 1,
                'is_default' => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'user_id' => 5,
                'workspace_id' => 1,
                'is_default' => true,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'user_id' => 6,
                'workspace_id' => 1,
                'is_default' => true,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            // Workspace 2 users with is_default = false
            [
                'user_id' => 1, // admin user
                'workspace_id' => 2,
                'is_default' => true,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'user_id' => 3,
                'workspace_id' => 2,
                'is_default' => false,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'user_id' => 4,
                'workspace_id' => 2,
                'is_default' => false,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'user_id' => 5,
                'workspace_id' => 2,
                'is_default' => false,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'user_id' => 6,
                'workspace_id' => 2,
                'is_default' => false,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
        ], ['user_id', 'workspace_id'], ['is_default', 'updated_at']);
    }
}
