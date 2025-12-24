<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar roles usando Spatie (firstOrCreate para evitar duplicação)
        $roles = [
            'Admin',
            'Pastor Presidente',
            'Pastor Auxiliar',
            'Secretaria',
            'Diaconos',
            'Lider de Ministerio',
            'Membro'
        ];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            if ($role->wasRecentlyCreated) {
                $this->command->info("✓ Role '{$roleName}' criado");
            } else {
                $this->command->info("Role '{$roleName}' já existe");
            }
        }

        $this->command->info("\n✓ Todos os roles foram verificados/criados com sucesso!");
    }
}
