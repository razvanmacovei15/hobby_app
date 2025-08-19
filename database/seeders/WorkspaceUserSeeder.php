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
        DB::table('workspace_users')->insert([
            [
                'user_id' => 1, // admin user
                'workspace_id' => 1, // Proiect Rezidențial Central Park
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'user_id' => 2, // dan boitos
                'workspace_id' => 1, // Proiect Rezidențial Central Park
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'user_id' => 3, // razvan macovei
                'workspace_id' => 1, // Proiect Rezidențial Central Park
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'user_id' => 1, // admin user
                'workspace_id' => 2, // Complex Comercial Plaza Mall
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'user_id' => 2, // dan boitos
                'workspace_id' => 2, // Complex Comercial Plaza Mall
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25),
            ],
            [
                'user_id' => 1, // admin user
                'workspace_id' => 3, // Bloc de Locuințe Green Valley
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'user_id' => 3, // razvan macovei
                'workspace_id' => 3, // Bloc de Locuințe Green Valley
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
        ]);
    }
}
