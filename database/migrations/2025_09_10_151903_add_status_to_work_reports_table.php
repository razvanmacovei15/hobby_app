<?php

use App\Enums\WorkReportStatus;
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
        Schema::table('work_reports', function (Blueprint $table) {
            $table->string('status')->default(WorkReportStatus::DRAFT->value)->after('notes');
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status', 'approved_at', 'approved_by']);
        });
    }
};
