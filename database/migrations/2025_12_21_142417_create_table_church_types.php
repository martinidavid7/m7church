<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // executar updates/deletes...

        Schema::create('church_types', function (Blueprint $table) {
            $table->id();
            $table->string('church_type');
            $table->timestamps();
        });

        Schema::table('churches', function (Blueprint $table) {
            $table->unsignedBigInteger('church_type_id')->after('logo');
            $table->foreign('church_type_id')
                ->references('id')
                ->on('church_types');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('churches', function (Blueprint $table) {
            $table->dropForeign(['church_type_id']);
            $table->dropColumn('church_type_id');
        });

        Schema::dropIfExists('church_types');
    }
};
