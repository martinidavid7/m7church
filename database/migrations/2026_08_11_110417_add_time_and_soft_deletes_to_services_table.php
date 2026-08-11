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
        Schema::table('services', function (Blueprint $table) {
            $table->time('time')->nullable()->after('day_of_week');
            $table->softDeletes();
        });

        Schema::table('service_type', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('time');
        });

        Schema::table('service_type', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
