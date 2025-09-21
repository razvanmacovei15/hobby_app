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
        Schema::table('building_permits', function (Blueprint $table) {
            $table->string('name')->after('permit_number');
            $table->string('height_regime')->nullable()->after('name');
            $table->string('land_book_number')->nullable()->after('height_regime');
            $table->string('cadastral_number')->nullable()->after('land_book_number');
            $table->string('architect')->nullable()->after('cadastral_number');
            $table->integer('execution_duration_days')->nullable()->after('architect');
            $table->string('image_url')->nullable()->after('execution_duration_days');
            $table->date('validity_term')->nullable()->after('image_url');
            $table->date('work_start_date')->nullable()->after('validity_term');
            $table->date('work_end_date')->nullable()->after('work_start_date');
            $table->foreignId('address_id')->nullable()->constrained()->after('work_end_date');
            $table->year('issuance_year')->after('address_id');
            
            // Create unique constraint on permit_number and issuance_year combination
            $table->unique(['permit_number', 'issuance_year'], 'permit_number_year_unique');
            
            // Add index for better performance
            $table->index(['work_start_date', 'work_end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('building_permits', function (Blueprint $table) {
            $table->dropIndex(['work_start_date', 'work_end_date']);
            $table->dropUnique('permit_number_year_unique');
            $table->dropForeign(['address_id']);
            $table->dropColumn([
                'name',
                'height_regime',
                'land_book_number',
                'cadastral_number',
                'architect',
                'execution_duration_days',
                'image_url',
                'validity_term',
                'work_start_date',
                'work_end_date',
                'address_id',
                'issuance_year'
            ]);
        });
    }
};
