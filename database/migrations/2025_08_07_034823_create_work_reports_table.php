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
        Schema::create('work_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->index()->constrained()->restrictOnDelete();
            $table->foreignId('contract_annex_id')->index()->constrained()->restrictOnDelete();
            $table->foreignId('written_by')->constrained('users')->cascadeOnDelete();

            $table->integer('report_month'); // e.g., 3
            $table->integer('report_year'); // e.g., 2025
            $table->integer('report_number'); // incrementing for all reports (handled in model)
            $table->text('notes')->nullable(); // optional notes or remarks

            $table->unique(['report_year', 'report_number']);
            $table->unique(['contract_id', 'report_year', 'report_month']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_reports');
    }
};
