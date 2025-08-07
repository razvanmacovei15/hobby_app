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
        Schema::create('contract_extra_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete(); // the executor of the extra service
            $table->string('name');
            $table->string('unit_of_measure')->nullable();
            $table->decimal('price_per_unit_of_measure', 10, 2);
            $table->integer('quantity')->default(1);
            $table->text('description')->nullable(); // optional explanation
            $table->date('provided_at'); // when it was done
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_extra_services');
    }
};
