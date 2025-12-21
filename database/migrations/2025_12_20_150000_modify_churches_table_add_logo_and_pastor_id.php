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
        Schema::table('churches', function (Blueprint $table) {
            // Remove old pastor fields
            $table->dropColumn(['pastor_name', 'pastor_phone', 'pastor_mail']);

            // Add new fields
            $table->foreignId('pastor_id')->nullable()->after('church_mail')->constrained('persons')->onDelete('set null');
            $table->string('logo')->nullable()->after('pastor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('churches', function (Blueprint $table) {
            // Remove new fields
            $table->dropForeign(['pastor_id']);
            $table->dropColumn(['pastor_id', 'logo']);

            // Restore old fields
            $table->string('pastor_name')->nullable();
            $table->string('pastor_phone')->nullable();
            $table->string('pastor_mail')->nullable();
        });
    }
};
