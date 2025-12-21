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
            // Sintaxe moderna para criar a coluna e a chave estrangeira.
            // Adicionamos ->nullable() para permitir que a coluna fique nula.
            $table->foreignId('city_id')->after('phone')->nullable()->constrained('cities');
        });

          Schema::table('churches', function (Blueprint $table) {
            $table->foreignId('city_id')->after('zip_code')->nullable()->constrained('cities');
          });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*Schema::table('churches', function (Blueprint $table) {
            // A ordem no down() deve ser o inverso do up().
            // 1. Remove a restrição de chave estrangeira.
            $table->dropForeign(['city_id']); // ou $table->dropConstrainedForeignId('city_id');
            // 2. Remove a coluna.
            $table->dropColumn('city_id');
        });*/
        Schema::table('persons', function (Blueprint $table) {
            // A ordem no down() deve ser o inverso do up().
            // 1. Remove a restrição de chave estrangeira.
            $table->dropForeign(['city_id']); // ou $table->dropConstrainedForeignId('city_id');
            // 2. Remove a coluna.
            $table->dropColumn('city_id');
        });
    }
};
