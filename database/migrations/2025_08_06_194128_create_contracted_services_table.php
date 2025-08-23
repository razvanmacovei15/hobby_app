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
        Schema::create('contracted_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_annex_id')->index()->constrained('contract_annexes')->restrictOnDelete();
            $table->integer('order');
            $table->string('name');
            $table->string('unit_of_measure');
            $table->decimal('price_per_unit_of_measure', 10, 2);

            $table->unique(['contract_annex_id', 'name']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracted_services');
    }
};
