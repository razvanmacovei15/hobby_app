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
        Schema::table('work_reports', function (Blueprint $table) {
            // Add beneficiary_id column
            $table->foreignId('beneficiary_id')
                ->after('id')
                ->constrained('companies');
            
            // Drop the existing foreign key on company_id
            $table->dropForeign(['company_id']);
            
            // Rename company_id to executor_id for clarity
            $table->renameColumn('company_id', 'executor_id');
        });
        
        // In a separate schema call, add the new foreign key constraint for executor_id
        Schema::table('work_reports', function (Blueprint $table) {
            $table->foreign('executor_id')
                ->references('id')
                ->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {
            // Remove beneficiary_id column
            $table->dropForeign(['beneficiary_id']);
            $table->dropColumn('beneficiary_id');
            
            // Drop executor_id foreign key and rename back to company_id
            $table->dropForeign(['executor_id']);
            $table->renameColumn('executor_id', 'company_id');
        });
        
        // Restore original company_id foreign key
        Schema::table('work_reports', function (Blueprint $table) {
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }
};
