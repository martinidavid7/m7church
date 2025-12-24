<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Se o usuário está autenticado mas não tem roles
        if ($user && $user->roles->isEmpty()) {
            // Criar ou buscar o role "Membro" (o mais baixo)
            $membroRole = Role::firstOrCreate(['name' => 'Membro']);

            // Atribuir o role "Membro" ao usuário
            $user->assignRole($membroRole);

            \Log::info("Role 'Membro' atribuído automaticamente ao usuário: {$user->email} (ID: {$user->id})");
        }

        return $next($request);
    }
}
