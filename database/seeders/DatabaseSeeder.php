<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\UfSeeder;
use Database\Seeders\CitiesSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ChurchTypeSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\ServiceTypeSeeder;



// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => '',
            'email' => 'test@example.com',
        ]);*/

        $this->call(AdminUserSeeder::class);
        $this->call(ChurchTypeSeeder::class);
        $this->call(UfSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ServiceTypeSeeder::class);

    }
}
