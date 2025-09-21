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
        Schema::table('workspace_executors', function (Blueprint $table) {
            // Update the enum to include all ExecutorType values
            $table->enum('executor_type', [
                'electrical', 
                'masonry', 
                'plumbing', 
                'facades', 
                'finishes',
                'structural',
                'demolition',
                'insulation'
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_executors', function (Blueprint $table) {
            // Revert to original enum values
            $table->enum('executor_type', [
                'electrical', 
                'masonry', 
                'plumbing', 
                'facades', 
                'finishes'
            ])->nullable()->change();
        });
    }
};
