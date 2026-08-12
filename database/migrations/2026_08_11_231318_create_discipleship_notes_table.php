<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discipleship_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipleship_id')->constrained('discipleships')->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('persons')->nullOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discipleship_notes');
    }
};
