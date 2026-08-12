<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Discipleship;
use App\Models\Ministry;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

trait AuthorizesDiscipulado
{
    use AuthorizesMinistryAccess;

    private function discipuladoMinistry(): Ministry
    {
        return Ministry::where('name', 'Discipulado')->firstOrFail();
    }

    private function authorizeDiscipuladoView(): void
    {
        $this->authorizeMinistryView($this->discipuladoMinistry());
    }

    private function authorizeDiscipuladoManage(): void
    {
        $this->authorizeMinistryManage($this->discipuladoMinistry());
    }

    private function canManageDiscipulado(): bool
    {
        return $this->isMinistryAdmin() || $this->isMinistryLeader($this->discipuladoMinistry());
    }

    /**
     * Acesso a um registro específico: administração global ou o próprio discipulador.
     */
    private function authorizeDiscipleshipAccess(Discipleship $discipleship): void
    {
        $person = $this->currentPerson();

        if ($this->isMinistryAdmin() || ($person && $discipleship->discipulador_id === $person->id)) {
            return;
        }

        throw new AccessDeniedHttpException('Você não tem acesso a este discipulado.');
    }

    /**
     * Restringe a listagem: administração global vê tudo; qualquer outra pessoa
     * (mesmo líder do módulo) só vê os discipulados que ela mesma conduz.
     */
    private function scopeDiscipleshipsToCurrentUser($query)
    {
        if ($this->isMinistryAdmin()) {
            return $query;
        }

        $person = $this->currentPerson();

        return $query->where('discipulador_id', $person?->id ?? 0);
    }
}
