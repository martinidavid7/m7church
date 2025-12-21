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
        Schema::create('churches', function (Blueprint $table) {
            $table->id();
            $table->string('church_name');
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('complement')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('church_mail')->nullable(); // E-mail da igreja, opcionalmente único
            $table->string('pastor_name')->nullable();
            $table->string('pastor_phone')->nullable();
            $table->string('pastor_mail')->nullable(); // E-mail do pastor, opcionalmente único

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('church');
    }
};
