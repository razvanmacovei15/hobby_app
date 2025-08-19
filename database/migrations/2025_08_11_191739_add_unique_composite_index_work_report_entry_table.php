<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_report_entries', function (Blueprint $table) {
            $table->unique(['work_report_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::table('work_report_entries', function (Blueprint $table) {
            $table->dropUnique(['work_report_entries_work_report_id_order_unique']);
        });
    }

};
