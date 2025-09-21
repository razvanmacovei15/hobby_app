<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, clean up duplicate permissions by keeping only one of each unique name
        DB::transaction(function () {
            // Get all permission names with their workspace_ids
            $duplicatePermissions = DB::table('permissions')
                ->select('name', 'guard_name')
                ->groupBy('name', 'guard_name')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            foreach ($duplicatePermissions as $duplicate) {
                // Keep the first permission and delete the rest
                $firstPermission = DB::table('permissions')
                    ->where('name', $duplicate->name)
                    ->where('guard_name', $duplicate->guard_name)
                    ->first();

                if ($firstPermission) {
                    // Delete all other permissions with the same name/guard
                    DB::table('permissions')
                        ->where('name', $duplicate->name)
                        ->where('guard_name', $duplicate->guard_name)
                        ->where('id', '!=', $firstPermission->id)
                        ->delete();

                    // Update the kept permission to remove category and description if empty
                    DB::table('permissions')
                        ->where('id', $firstPermission->id)
                        ->update([
                            'workspace_id' => null
                        ]);
                }
            }
        });

        Schema::table('permissions', function (Blueprint $table) {
            // Remove the workspace_id column and its foreign key constraint
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Add back the workspace_id column and foreign key constraint
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
        });
    }
};
