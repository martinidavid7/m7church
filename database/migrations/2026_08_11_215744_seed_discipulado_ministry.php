<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('ministries')->updateOrInsert(
            ['name' => 'Discipulado'],
            [
                'description' => 'Módulo de discipulado — acompanhamento espiritual de membros e visitantes.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('ministries')->where('name', 'Discipulado')->delete();
    }
};
