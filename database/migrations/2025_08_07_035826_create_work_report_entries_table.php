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
        Schema::create('work_report_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_report_id')->index()->constrained()->cascadeOnDelete();

            $table->string('service_type'); // ContractService or WorkReportExtraService
            $table->unsignedBigInteger('service_id'); // ID of the service

            $table->decimal('quantity', 10, 2);
            $table->decimal('total', 10, 2);

            $table->integer('order');
            $table->string('notes')->nullable();

            $table->timestamps();

            $table->index(['work_report_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_report_entries');
    }
};
