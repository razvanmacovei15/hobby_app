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
        Schema::create('contract_annexes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->index()->constrained('contracts')->restrictOnDelete();
            $table->integer('annex_number')->unique();
            $table->date('sign_date');
            $table->text('notes')->nullable();

            $table->unique(['contract_id', 'annex_number']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_annexes');
    }
};
