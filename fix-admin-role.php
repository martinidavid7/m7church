<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

// Buscar o usuário
$user = User::where('email', 'martins.david@gmail.com')->first();

if (!$user) {
    echo "Usuário não encontrado!\n";
    exit(1);
}

echo "Usuário encontrado:\n";
echo "ID: {$user->id}\n";
echo "Nome: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Roles atuais: " . $user->roles->pluck('name')->implode(', ') . "\n\n";

// Verificar se o role Admin existe
$adminRole = Role::where('name', 'Admin')->first();

if (!$adminRole) {
    echo "Role 'Admin' não existe! Criando...\n";
    $adminRole = Role::create(['name' => 'Admin']);
}

// Atribuir role Admin ao usuário
$user->syncRoles(['Admin']);

echo "Role Admin atribuído com sucesso!\n";
echo "Roles após atualização: " . $user->roles->pluck('name')->implode(', ') . "\n";
