<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChurchTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('church_types')->insert([
            ['church_type' => 'Matriz', 'created_at' => now(), 'updated_at' => now()],
            ['church_type' => 'Igreja Filha', 'created_at' => now(), 'updated_at' => now()],
            ['church_type' => 'Congregação', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
