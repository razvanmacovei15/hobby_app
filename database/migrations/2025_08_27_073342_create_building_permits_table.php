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
        Schema::create('building_permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->unique();
            $table->string('permit_type');
            $table->string('status');
            $table->foreignId('workspace_id')->constrained()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_permits');
    }
};
