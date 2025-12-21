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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('marital_status')->nullable();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('complement')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('landline_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('profession')->nullable();
            $table->string('education_level')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('mail')->nullable();
            $table->boolean('accept_receive_messages')->default(false);
            $table->text('observations')->nullable();
            $table->timestamps();

            // Índices e chaves estrangeiras
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->nullOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('visitors');
    }
};
