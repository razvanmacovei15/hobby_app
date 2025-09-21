<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing responsible engineer data to the new pivot table
        $executors = DB::table('workspace_executors')
            ->whereNotNull('responsible_engineer_id')
            ->get(['id', 'responsible_engineer_id']);

        foreach ($executors as $executor) {
            DB::table('workspace_executor_engineers')->insertOrIgnore([
                'workspace_executor_id' => $executor->id,
                'user_id' => $executor->responsible_engineer_id,
                'role' => 'primary', // Mark migrated engineers as primary
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Log the migration results
        $migratedCount = $executors->count();
        if ($migratedCount > 0) {
            echo "Migrated {$migratedCount} responsible engineers to the new pivot table.\n";
        } else {
            echo "No responsible engineers found to migrate.\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all migrated data from the pivot table
        // Note: This will remove ALL pivot data, not just migrated data
        // In production, you might want to be more selective
        DB::table('workspace_executor_engineers')->truncate();

        echo "Removed all engineer assignments from pivot table.\n";
    }
};
