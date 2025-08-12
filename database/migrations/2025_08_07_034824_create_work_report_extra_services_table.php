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
        Schema::create('work_report_extra_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_report_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('contract_id')->index()->constrained('contracts');
            $table->foreignId('executor_company_id')->constrained('companies'); // the executor of the extra service
            $table->foreignId('beneficiary_company_id')->constrained('companies'); // the beneficiary of the extra service

            $table->string('name');
            $table->string('unit_of_measure')->nullable();
            $table->decimal('price_per_unit_of_measure', 10, 2);
            $table->text('notes')->nullable(); // optional explanation

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
