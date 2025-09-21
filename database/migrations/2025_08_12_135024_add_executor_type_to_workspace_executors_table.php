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
            $table->enum('executor_type', ['electrical', 'masonry', 'plumbing', 'facades', 'finishes'])->nullable()->after('executor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_executors', function (Blueprint $table) {
            $table->dropColumn('executor_type');
        });
    }
};
