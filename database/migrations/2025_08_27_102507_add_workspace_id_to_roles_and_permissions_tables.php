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
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('workspace_id')->nullable()->constrained()->onDelete('cascade');
            
            // Make name unique per workspace (or globally if no workspace)
            $table->dropUnique(['name', 'guard_name']);
            $table->unique(['name', 'guard_name', 'workspace_id']);
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->foreignId('workspace_id')->nullable()->constrained()->onDelete('cascade');
            
            // Make name unique per workspace (or globally if no workspace)
            $table->dropUnique(['name', 'guard_name']);
            $table->unique(['name', 'guard_name', 'workspace_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropUnique(['name', 'guard_name', 'workspace_id']);
            $table->dropColumn('workspace_id');
            $table->unique(['name', 'guard_name']);
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropUnique(['name', 'guard_name', 'workspace_id']);
            $table->dropColumn('workspace_id');
            $table->unique(['name', 'guard_name']);
        });
    }
};
