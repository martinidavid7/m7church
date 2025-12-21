<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UF;

class UfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        UF::create(['uf' => 'AC', 'name' => 'Acre']);
        UF::create(['uf' => 'AL', 'name' => 'Alagoas']);
        UF::create(['uf' => 'AP', 'name' => 'Amapá']);
        UF::create(['uf' => 'AM', 'name' => 'Amazonas']);
        UF::create(['uf' => 'BA', 'name' => 'Bahia']);
        UF::create(['uf' => 'CE', 'name' => 'Ceará']);
        UF::create(['uf' => 'DF', 'name' => 'Distrito Federal']);
        UF::create(['uf' => 'ES', 'name' => 'Espírito Santo']);
        UF::create(['uf' => 'GO', 'name' => 'Goiás']);
        UF::create(['uf' => 'MA', 'name' => 'Maranhão']);
        UF::create(['uf' => 'MT', 'name' => 'Mato Grosso']);
        UF::create(['uf' => 'MS', 'name' => 'Mato grosso do Sul']);
        UF::create(['uf' => 'MG', 'name' => 'Minas Gerais']);
        UF::create(['uf' => 'PA', 'name' => 'Pará']);
        UF::create(['uf' => 'PB', 'name' => 'Paraíba']);
        UF::create(['uf' => 'PR', 'name' => 'Paraná']);
        UF::create(['uf' => 'PE', 'name' => 'Pernambuco']);
        UF::create(['uf' => 'PI', 'name' => 'Piauí']);
        UF::create(['uf' => 'RJ', 'name' => 'Rio de Janeiro']);
        UF::create(['uf' => 'RN', 'name' => 'Rio Grande do Norte']);
        UF::create(['uf' => 'RS', 'name' => 'Rio Grande do Sul']);
        UF::create(['uf' => 'RO', 'name' => 'Rondônia']);
        UF::create(['uf' => 'RR', 'name' => 'Roraima']);
        UF::create(['uf' => 'SC', 'name' => 'Santa Catarina']);
        UF::create(['uf' => 'SP', 'name' => 'São Paulo']);
        UF::create(['uf' => 'SE', 'name' => 'Sergipe']);
        UF::create(['uf' => 'TO', 'name' => 'Tocantins']);
    }
}
