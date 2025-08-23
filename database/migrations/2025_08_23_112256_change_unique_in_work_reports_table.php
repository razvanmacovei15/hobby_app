<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {
            // drop the old unique index (name from your error)
            $table->dropUnique('work_reports_report_year_report_number_unique');

            // add a scoped unique index: company + year + number
            $table->unique(
                ['company_id', 'report_year', 'report_number'],
                'work_reports_company_year_number_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {
            $table->dropUnique('work_reports_company_year_number_unique');
            $table->unique(['report_year', 'report_number'], 'work_reports_report_year_report_number_unique');
        });
    }
};
