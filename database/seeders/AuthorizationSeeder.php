<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder orchestrates the complete authorization setup by running
     * the PermissionSeeder first, then the RoleSeeder for all workspaces.
     */
    public function run(): void
    {
        echo "🚀 Starting complete authorization setup...\n\n";

        echo "📋 Step 1: Setting up permissions for all workspaces...\n";
        $this->call(PermissionSeeder::class);

        echo "\n👤 Step 2: Setting up roles for all workspaces...\n";
        $this->call(RoleSeeder::class);

        echo "\n✅ Complete authorization setup finished successfully!\n";
        echo "You can now assign roles to users in the admin panel.\n";
    }
}
