<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Ministry;
use App\Models\Person;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

trait AuthorizesMinistryAccess
{
    private const MINISTRY_ADMIN_ROLES = ['Admin', 'Pastor Presidente', 'Pastor Auxiliar', 'Secretaria'];

    private function currentPerson(): ?Person
    {
        return Person::where('user_id', auth()->id())->first();
    }

    private function isMinistryAdmin(): bool
    {
        return auth()->user()->hasAnyRole(self::MINISTRY_ADMIN_ROLES);
    }

    private function isMinistryLeader(Ministry $ministry): bool
    {
        $person = $this->currentPerson();

        return $person && $person->isLeaderOf($ministry->id);
    }

    private function isMinistryMember(Ministry $ministry): bool
    {
        $person = $this->currentPerson();

        return $person && $person->ministries()->where('ministries.id', $ministry->id)->exists();
    }

    /**
     * Permite visualizar: administração, líderes e membros do ministério.
     */
    private function authorizeMinistryView(Ministry $ministry): void
    {
        if ($this->isMinistryAdmin() || $this->isMinistryLeader($ministry) || $this->isMinistryMember($ministry)) {
            return;
        }

        throw new AccessDeniedHttpException('Você não tem acesso a este ministério.');
    }

    /**
     * Permite gerenciar (editar/criar escalas etc.): administração e líderes do ministério.
     */
    private function authorizeMinistryManage(Ministry $ministry): void
    {
        if ($this->isMinistryAdmin() || $this->isMinistryLeader($ministry)) {
            return;
        }

        throw new AccessDeniedHttpException('Você não tem permissão para gerenciar este ministério.');
    }
}
