<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_type')->insert([
            ['service_type' => 'Cultos Normais', 'created_at' => now(), 'updated_at' => now()],
            ['service_type' => 'Cultos nos Lares', 'created_at' => now(), 'updated_at' => now()],
            ['service_type' => 'Pequenos Grupos', 'created_at' => now(), 'updated_at' => now()],
            ['service_type' => 'Células', 'created_at' => now(), 'updated_at' => now()],
            ['service_type' => 'EBD', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
