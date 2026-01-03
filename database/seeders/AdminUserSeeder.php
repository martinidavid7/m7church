<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Person;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Email do administrador (pode ser alterado via variável de ambiente)
        $adminEmail = env('ADMIN_EMAIL', 'admin@m7church.com');
        $adminName = env('ADMIN_NAME', 'Administrador');
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');

        // Buscar ou criar o usuário
        $user = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'active' => 1,
            ]
        );

        if ($user->wasRecentlyCreated) {
            $this->command->info("✓ Novo usuário Admin criado:");
        } else {
            $this->command->info("Usuário Admin já existe:");
        }

        $this->command->info("ID: {$user->id}");
        $this->command->info("Nome: {$user->name}");
        $this->command->info("Email: {$user->email}");
        $this->command->info("Roles atuais: " . ($user->roles->pluck('name')->implode(', ') ?: 'Nenhum'));

        // Verificar se o role Admin existe
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        if ($adminRole->wasRecentlyCreated) {
            $this->command->warn("Role 'Admin' criado!");
        }

        // Atribuir role Admin ao usuário
        $user->syncRoles(['Admin']);

        // Criar registro de pessoa se não existir
        $person = Person::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'mail' => $user->email,
                'active' => 1,
            ]
        );

        if ($person->wasRecentlyCreated) {
            $this->command->info("✓ Registro de pessoa criado para o Admin");
        }

        $this->command->info("✓ Role Admin atribuído com sucesso!");
        $this->command->info("Roles após atualização: " . $user->fresh()->roles->pluck('name')->implode(', '));

        if ($user->wasRecentlyCreated) {
            $this->command->warn("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->command->warn("CREDENCIAIS DO ADMINISTRADOR:");
            $this->command->warn("Email: {$adminEmail}");
            $this->command->warn("Senha: {$adminPassword}");
            $this->command->warn("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->command->error("⚠ IMPORTANTE: Altere a senha após o primeiro login!");
        }
    }
}
