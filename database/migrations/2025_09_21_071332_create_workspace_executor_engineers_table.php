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
        Schema::create('workspace_executor_engineers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workspace_executor_id')
                ->constrained('workspace_executors')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('role')->default('engineer'); // 'primary', 'secondary', 'supervisor', 'engineer'
            $table->timestamp('assigned_at')->useCurrent();

            $table->timestamps();

            // Ensure a user can only be assigned once per executor
            $table->unique(['workspace_executor_id', 'user_id'], 'wee_executor_user_unique');

            // Indexes for performance
            $table->index(['workspace_executor_id', 'role']);
            $table->index(['user_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_executor_engineers');
    }
};
