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
                'name' => 'Elite City',
                'owner_id' => 1, // Construct Pro SRL
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'name' => 'Elite Junior',
                'owner_id' => 1, // Construct Pro SRL (same owner as workspace 1)
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'name' => 'Elite Town',
                'owner_id' => 1, // Construct Pro SRL (same owner as workspace 1)
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
        ]);


    }
}
