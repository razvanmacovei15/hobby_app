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
            $table->foreignId('responsible_engineer_id')
                ->nullable()
                ->after('executor_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index(['responsible_engineer_id', 'workspace_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_executors', function (Blueprint $table) {
            $table->dropIndex(['responsible_engineer_id', 'workspace_id']);
            $table->dropForeign(['responsible_engineer_id']);
            $table->dropColumn('responsible_engineer_id');
        });
    }
};
