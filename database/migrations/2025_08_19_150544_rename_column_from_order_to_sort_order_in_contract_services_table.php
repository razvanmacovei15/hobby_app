<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_services', function (Blueprint $table) {
            $table->renameColumn('order', 'sort_order');
        });

        Schema::table('contract_services', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('contract_services', function (Blueprint $table) {
            $table->renameColumn('sort_order', 'order');
        });

        Schema::table('contract_services', function (Blueprint $table) {
            $table->unsignedInteger('order')->nullable(false)->change();
        });
    }
};
