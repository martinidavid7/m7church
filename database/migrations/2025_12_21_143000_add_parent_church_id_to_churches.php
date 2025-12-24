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
            $table->unsignedBigInteger('parent_church_id')->nullable()->after('church_type_id');
            $table->foreign('parent_church_id')
                ->references('id')
                ->on('churches')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('churches', function (Blueprint $table) {
            $table->dropForeign(['parent_church_id']);
            $table->dropColumn('parent_church_id');
        });
    }
};
