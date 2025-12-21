<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitiesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $city = new City();
        $city->name = 'São Paulo';
        $city->uf_id = 25;
        $city->save();

        $city = new City();
        $city->name = 'Cordeirópolis';
        $city->uf_id = 25;
        $city->save();
        
    }
}
