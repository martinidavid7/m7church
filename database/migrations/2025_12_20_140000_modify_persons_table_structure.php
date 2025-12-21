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
        Schema::table('persons', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn(['first_name', 'last_name', 'phone']);

            // Add new columns
            $table->string('name')->after('id');
            $table->date('birth_date')->nullable()->after('name');
            $table->enum('gender', ['M', 'F'])->nullable()->after('birth_date');
            $table->string('marital_status')->nullable()->after('gender');
            $table->string('landline_phone')->nullable()->after('zip_code');
            $table->string('mobile_phone')->nullable()->after('landline_phone');
            $table->string('profession')->nullable()->after('mobile_phone');
            $table->string('education_level')->nullable()->after('profession');
            $table->string('photo')->nullable()->after('education_level');

            // Change baptism_date from dateTime to date
            $table->date('baptism_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            // Restore old columns
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('phone')->nullable()->after('zip_code');

            // Remove new columns
            $table->dropColumn([
                'name',
                'birth_date',
                'gender',
                'marital_status',
                'landline_phone',
                'mobile_phone',
                'profession',
                'education_level',
                'photo'
            ]);

            // Restore baptism_date to dateTime
            $table->dateTime('baptism_date')->nullable()->change();
        });
    }
};
