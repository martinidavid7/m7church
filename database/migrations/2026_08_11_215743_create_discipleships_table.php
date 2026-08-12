<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discipleships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipulador_id')->constrained('persons')->cascadeOnDelete();
            $table->morphs('discipulado');
            $table->enum('status', ['active', 'ended'])->default('active');
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['discipulador_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discipleships');
    }
};
