<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            // Basic address
            $table->string('city');
            $table->string('street');
            $table->string('street_number');
            $table->string('building')->nullable();
            $table->string('apartment_number')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Romania');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
