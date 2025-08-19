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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('j')->unique()->nullable();
            $table->string('cui')->unique()->nullable();
            $table->string('place_of_registration')->nullable();
            $table->string('iban')->nullable()->unique();
            $table->foreignId('representative_id')->nullable()->constrained('users');
            $table->string('phone')->nullable();
            $table->foreignId('address_id')->nullable()->constrained('addresses')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
